<?php

namespace App\Class;

use Illuminate\Database\Eloquent\Model;
use App\Service\SignService;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class XMLFormatter
{

    public SignService $signService;

    /**
     * Xml Path
     *
     * @var string
     */
    private string $xmlPath = 'xml/';

    public function __construct(SignService $signService)
    {
        $this->signService = $signService;
    }

    /**
     * Generate XML 
     *
     * @param Model $invoice
     * @return string | bool
     */
    public function generateInvoice(Model $invoice) : string | bool
    {
        $invoiceData = $invoice->load('items')->toArray();


        //TODO
        /**
         * DATOS DEL CLIENTE
         */
        $invoiceData['razonSocial'] = 'VELIZ BERZOSA JORGE LUIS';
        $invoiceData['nombreComercial'] = 'VELIZ BERZOSA JORGE LUIS';
        $invoiceData['ruc'] = '0926894544001';
        $invoiceData['estab'] = '01';
        $invoiceData['ptoEmi'] = '01';
        $invoiceData['secuencial'] = '001002'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['dirMatriz'] = 'ALGUN LUGAR DE ESTE GRAN PAIS'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['dirEstablecimiento'] = 'ESTABLECIMIENTO'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['obligadoContabilidad'] = 'NO'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['tipoIdentificacionComprador'] = '01'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE



        $xml = new \SimpleXMLElement('<factura/>');
        $xml->addAttribute('id', 'comprobante');
        $xml->addAttribute('version', '1.0.0');

        $infoTributaria = $xml->addChild('infoTributaria');
        $infoTributaria->addChild('ambiente', '1');
        $infoTributaria->addChild('tipoEmision', '1');
        $infoTributaria->addChild('razonSocial', $invoiceData['razonSocial']);
        $infoTributaria->addChild('nombreComercial', $invoiceData['nombreComercial']);
        $infoTributaria->addChild('ruc', $invoiceData['ruc']);
        $infoTributaria->addChild('claveAcceso', $invoiceData['access_key']);
        $infoTributaria->addChild('codDoc', '01');
        $infoTributaria->addChild('estab', $invoiceData['estab']);
        $infoTributaria->addChild('ptoEmi', $invoiceData['ptoEmi']);
        $infoTributaria->addChild('secuencial', $invoiceData['secuencial']);
        $infoTributaria->addChild('dirMatriz', $invoiceData['dirMatriz']);

        $infoFactura = $xml->addChild('infoFactura');
        $infoFactura->addChild('fechaEmision', $invoiceData['created_at']);
        $infoFactura->addChild('dirEstablecimiento', $invoiceData['dirEstablecimiento']);
        $infoFactura->addChild('obligadoContabilidad', $invoiceData['obligadoContabilidad']);
        $infoFactura->addChild('tipoIdentificacionComprador', $invoiceData['tipoIdentificacionComprador']);
        $infoFactura->addChild('razonSocialComprador', $invoiceData['customer_name']);
        $infoFactura->addChild('identificacionComprador', $invoiceData['customer_identification']);
        $infoFactura->addChild('totalSinImpuestos', $invoiceData['subtotal']);
        $infoFactura->addChild('totalDescuento', 0); // TODO : EXTRAER EL DESCUENTO

        $totalConImpuestos = $infoFactura->addChild('totalConImpuestos');
        // foreach ($invoiceData['impuestos'] as $impuesto) {
        $totalImpuesto = $totalConImpuestos->addChild('totalImpuesto');
        $totalImpuesto->addChild('codigo', 2); // TODO EL CODIGO 2 ES IVA, VER REFERENCIA DEL SRI Y LA CONFIGURACION DEL PRODUCTO
        $totalImpuesto->addChild('codigoPorcentaje', 3); // TODO ESTO ES EL CODIGO DE IVA AL 14% SE DEBE REVISAR DE ACUERDO A LA CONFIGURACION DEL PRODUCTO
        $totalImpuesto->addChild('baseImponible', $invoiceData['subtotal']);
        $totalImpuesto->addChild('valor', $invoiceData['total']);
        // }

        $infoFactura->addChild('propina', 0);
        $infoFactura->addChild('importeTotal', $invoiceData['total']);
        $infoFactura->addChild('moneda', 'DOLAR');

        $detalles = $xml->addChild('detalles');
        foreach ($invoiceData['items'] as $detalle) {
            $detalleNode = $detalles->addChild('detalle');
            $detalleNode->addChild('codigoPrincipal', 14654); // TODO ENVIAR EL CODIGO DEL PRODUCTO TMB
            $detalleNode->addChild('descripcion', $detalle['description']);
            $detalleNode->addChild('cantidad', $detalle['quantity']);
            $detalleNode->addChild('precioUnitario', $detalle['unit_price']);
            $detalleNode->addChild('descuento', 0); // TODO DEPENDE DE LA CONFIGURACION DEL PRODUCT
            $detalleNode->addChild('precioTotalSinImpuesto', $detalle['unit_price']);
        }

        $xml = $xml->asXML();
        $xmlName = Str::snake($invoiceData['razonSocial']);
        $docName = $this->xmlPath . now()->format('Ymd') . '_' . $xmlName . '_SF.xml';
        Storage::put($docName, $xml);
        return $this->signXML($docName);
        
    }


    /**
     * Sign XML Document
     * @param string $xml
     * @return string | bool
     */
    public function signXML(string $xmlDocName)  : string | bool
    {
        // TODO TODO ESTO DEBE VENIR DEL CLIENTE QUE SE VA A FIRMAR
        $password = 'jfTGlm51u9';
        $certificateFilePath = storage_path('app/certificates/certificate.p12');
        $pathXml = storage_path('app/' . $xmlDocName);
        $pathSignXml = storage_path('app/' . $this->xmlPath);
        $xmlDocNameSigned = explode('/', $xmlDocName)[1];
        $docSigned = str_replace('_SF', '_F', $xmlDocNameSigned);


        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $result = Process::path(storage_path('app/signature/'))->run("java -jar sri.jar $certificateFilePath  $password $pathXml $pathSignXml $docSigned");
        } else {
            $result = Process::path(storage_path('app/signature/'))->run("/usr/bin/java -jar -jar sri.jar $certificateFilePath  $password $pathXml $pathSignXml $docSigned");
        }
        $output = $result->successful();
        if (!$output) {
            logger()->error('Error al firmar Documento ' . $xmlDocName . ' ' . $result->errorOutput());
            return false;
        } else {
            logger()->info('Documento ' . $xmlDocName . ' Firmado correctamente');
            return $pathSignXml;
        }



        // $output = $result->successful();
    }
}
