<?php

// get Url Client Soaps

return [

    // Url Reception

    'url_reception' => env('SRI_URL_RECEPTION', 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl'),



    // URL Authorization

    'url_authorization' => env('SRI_URL_AUTHORIZATION', 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl')




];