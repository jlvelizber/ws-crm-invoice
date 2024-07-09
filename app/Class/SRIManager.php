<?php

namespace App\Class;

use App\Enums\DocumentSRITypeEnum;
use App\Enums\InvoiceStatusEnum;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SoapClient;

class SRIManager
{

    private SoapClient $soapClient;

    /**
     * Genera la Clave de acceso
     *
     * @param Model $invoice
     * @param DocumentSRITypeEnum $documentType
     * @return string
     */
    public function generateAccessKeyCode(Model $invoice, DocumentSRITypeEnum $documentType): string
    {
        $fechaEmision = $invoice->created_at;
        // Fecha de emisión en formato ddmmaaaa
        $fecha = new DateTime($fechaEmision);
        $fechaEmisionFormatted = $fecha->format('dmY');
        $tipoComprobante = $documentType->value;
        $ruc = '0926894544001'; //TODO SACAR DESDE EL CLIENTE
        $ambiente = 1; // TODO SACAR DESDE EL CLIENTE
        $serie = '001001'; //TODO: SACAR DESDE EL CLIENTE
        $numeroComprobante = '001002'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
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
            'cache_wsdl' => WSDL_CACHE_NONE
        ];


        array_push(
            $config,
            $customConfigSoap
        );


        $this->soapClient = new SoapClient($url, $config);
    }

    /**
     * Valida el Comprobante XML enviado al SRI
     *
     * @param string $xmlSigned
     * @return bool
     */
    public function sedReceptionSRI(string $xmlSigned): bool
    {
        $xmlContent = file_get_contents($xmlSigned);
        if (!$xmlContent) {
            logger()->error('Error al enviar la recepcion del XML ' . $xmlSigned);
            return false;
        }


        $this->setSoapClient(config('sri.url_reception'));

        $response = $this->soapClient->validarComprobante(['xml' => $xmlContent]);
        // dd($response->RespuestaRecepcionComprobante);
        if ($response->RespuestaRecepcionComprobante->estado === InvoiceStatusEnum::SRI_WDSL_STATUS_RECIEVED->value) {
            logger()->info('factura con ruta ' . $xmlSigned . ' ha sido recibida por la entidad del SRI');
            return true;
        }


        return false;

    }
}
