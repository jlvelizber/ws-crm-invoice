<?php

namespace App\Class;

use App\Enums\SRI\SRIDocumentTypeEnum;
use App\Enums\InvoiceStatusEnum;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use SoapClient;
use SoapFault;

class SRIManager
{

    private SoapClient $soapClient;

    /**
     * Genera la Clave de acceso
     *
     * @param Model $invoice
     * @param SRIDocumentTypeEnum $documentType
     * @return string
     */
    public function generateAccessKeyCode(Model $invoice, SRIDocumentTypeEnum $documentType): string
    {
        $fechaEmision = $invoice->created_at;
        // Fecha de emisión en formato ddmmaaaa
        $fecha = new DateTime($fechaEmision);
        $fechaEmisionFormatted = $fecha->format('dmY');
        $tipoComprobante = $documentType->value;
        $ruc = '0926894544001'; //TODO SACAR DESDE EL CLIENTE
        $ambiente = 1; // TODO SACAR DESDE EL CLIENTE
        $serie = '001001'; //TODO: SACAR DESDE EL CLIENTE
        $numeroComprobante = '001016'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $tipoEmision = 1; // TODO: NORMAL SACAR DESDE LA CONFIGURACION DEL CLIENTE

        // Código numérico aleatorio de 8 dígitos
        $codigoNumerico = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        // Concatenar los datos para formar la clave de acceso sin el dígito verificador
        $claveAccesoSinDV = $fechaEmisionFormatted .
            str_pad($tipoComprobante, 2, '0', STR_PAD_LEFT) .
            str_pad($ruc, 13, '0', STR_PAD_LEFT) .
            str_pad($ambiente, 1, '0', STR_PAD_LEFT) .
            str_pad($serie, 6, '0', STR_PAD_LEFT) .
            str_pad($numeroComprobante, 9, '0', STR_PAD_LEFT) .
            $codigoNumerico .
            str_pad($tipoEmision, 1, '0', STR_PAD_LEFT);

        // Calcular el dígito verificador
        $digitoVerificador = $this->generateModule11($claveAccesoSinDV);

        // Concatenar la clave de acceso con el dígito verificador
        $claveAcceso = $claveAccesoSinDV . $digitoVerificador;

        return $claveAcceso;
    }

    /**
     * Genera Module 11
     *
     * @param string $claveAccesoSinDV
     * @return string
     */
    private function generateModule11(string $claveAccesoSinDV): string
    {
        $factor = 2;
        $suma = 0;
        for ($i = strlen($claveAccesoSinDV) - 1; $i >= 0; $i--) {
            $suma += $factor * $claveAccesoSinDV[$i];
            $factor = $factor == 7 ? 2 : $factor + 1;
        }
        $modulo11 = 11 - ($suma % 11);
        $digitoVerificador = ($modulo11 == 11) ? 0 : ($modulo11 == 10 ? 1 : $modulo11);
        return $digitoVerificador;
    }


    private function setSoapClient($url, $customConfigSoap = []): void
    {
        $config = [
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'connection_timeout' => config('sri.timeout_connection')
        ];


        array_push(
            $config,
            $customConfigSoap
        );

        try {
            $this->soapClient = new SoapClient($url, $config);
        } catch (SoapFault $e) {
            dd($e);
        }
    }

    /**
     * Valida el Comprobante XML enviado al SRI
     *
     * @param string $xmlSigned
     * @return bool
     */
    public function sedReceptionSRI(string $xmlSigned, string|int $accessKey): bool
    {
        $xmlContent = file_get_contents($xmlSigned);
        if (!$xmlContent) {
            logger()->error('Error al enviar la recepcion del XML ' . $xmlSigned);
            return false;
        }


        $this->setSoapClient(config('sri.url_reception'));

        $response = $this->soapClient->validarComprobante(['xml' => $xmlContent]);

        $stateDocument = $response->RespuestaRecepcionComprobante->estado;

        if ($stateDocument === InvoiceStatusEnum::SRI_WDSL_STATUS_RECIEVED->value) {
            logger()->info('factura con codigo de acceso ' . $accessKey . ' ha sido recibida por la entidad del SRI');
            return true;
        }
        $codeValidationSRI = $response->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->identificador;
        $messageValidationSRI = $response->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->mensaje;
        logger()->error('factura con codigo de acceso ' . $accessKey . ' no ha sido recibida por la entidad del SRI: Razon .-' . $stateDocument . ' ' . $codeValidationSRI . '- ' . $messageValidationSRI);
        return false;

    }
    /**
     * Send to Confirmation SRI
     * @param mixed $xmlSigned
     * @return bool
     */
    public function sendConfirmationSRI(string $accessKey): bool
    {
        $this->setSoapClient(config('sri.url_authorization'));

        $response = $this->soapClient->autorizacionComprobante(['claveAccesoComprobante' => $accessKey]);

        if ($response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado === InvoiceStatusEnum::SRI_WDSL_STATUS_AUTHORIZED->value) {
            $authNum = $response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            logger()->info('factura con con codigo de acceso ' . $accessKey . ' ha sido AUTORIZADA por la entidad del SRI :  num autorizacion : ' . $authNum);
            return true;
        }

        logger()->error('factura con con codigo de acceso ' . $accessKey . ' no  ha sido recibida por la entidad del SRI ');
        return false;


    }


    /**
     * Send to Reception and Confirm to SRI
     * @param string $xmlSigned
     * @param string $accessKey
     * @return bool
     */
    public function sendToSRI(string $xmlSigned, string $accessKey): bool
    {
        $wasRecepted = $this->sedReceptionSRI($xmlSigned, $accessKey);
        if ($wasRecepted)
            return $this->sendConfirmationSRI($accessKey);
        return false;
    }
}
