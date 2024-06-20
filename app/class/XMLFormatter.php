<?php

namespace App\Class;

use Illuminate\Database\Eloquent\Model;

class XMLFormatter
{
    public static function XMLInvoice(Model $invoice)
    {
        $invoiceData = $invoice->toArray();


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
        $infoTributaria->addChild('claveAcceso', $invoiceData['claveAcceso']);
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
            $totalImpuesto->addChild('codigo', $invoiceData['codigo']);
            $totalImpuesto->addChild('codigoPorcentaje', $invoiceData['codigoPorcentaje']);
            $totalImpuesto->addChild('baseImponible', $invoiceData['baseImponible']);
            $totalImpuesto->addChild('valor', $invoiceData['valor']);
        // }

        $infoFactura->addChild('propina', $invoiceData['propina']);
        $infoFactura->addChild('importeTotal', $invoiceData['importeTotal']);
        $infoFactura->addChild('moneda', 'DOLAR');

        $detalles = $xml->addChild('detalles');
        foreach ($invoiceData['detalles'] as $detalle) {
            $detalleNode = $detalles->addChild('detalle');
            $detalleNode->addChild('codigoPrincipal', $detalle['codigoPrincipal']);
            $detalleNode->addChild('descripcion', $detalle['descripcion']);
            $detalleNode->addChild('cantidad', $detalle['cantidad']);
            $detalleNode->addChild('precioUnitario', $detalle['precioUnitario']);
            $detalleNode->addChild('descuento', $detalle['descuento']);
            $detalleNode->addChild('precioTotalSinImpuesto', $detalle['precioTotalSinImpuesto']);
        }

        return $xml->asXML();
    }
}
