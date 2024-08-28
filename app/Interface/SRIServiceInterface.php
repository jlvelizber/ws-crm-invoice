<?php
namespace App\Interface;

use App\Models\Customer;
use Illuminate\Http\Request;

interface SRIServiceInterface
{
    /**
     * Encargada de procesar el documento electronico con el SRI
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function process(Request $request);

    /**
     * Encargada de crear o actualizar el cliente
     * @param array $data
     * @return void
     */
    public function manageCustomer(array $data): Customer;
}