<?php

namespace App\Class;

use Illuminate\Database\Eloquent\Model;
use App\Service\SignService;

class XMLFormatter
{

    public SignService $signService;

    public function __construct(SignService $signService)
    {
        $this->signService = $signService;
    }

    public static function XMLInvoice(Model $invoice)
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
        $invoiceData['secuencial'] =  '001002'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['dirMatriz'] =  'ALGUN LUGAR DE ESTE GRAN PAIS'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['dirEstablecimiento'] =  'ESTABLECIMIENTO'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['obligadoContabilidad'] =  'NO'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE
        $invoiceData['tipoIdentificacionComprador'] =  '01'; //TODO SACAR DESDE LA ULTIMA FACTURA DEL CLIENTE



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
        $infoFactura->addChild('totalDescuento', 0); // TODO 

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
        self::signXML($xml);
    }


    /**
     * Sign XML Document
     * @param string $xml
     * @return string
     */
    public static function signXML(string $xml)
    {
        return $this->signService->signDocument($xml, 'certificate.p12', '123456');
    }
}
