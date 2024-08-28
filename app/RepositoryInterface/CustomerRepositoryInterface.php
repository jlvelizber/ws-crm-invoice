<?php

namespace App\RepositoryInterface;
use App\Interface\CrudRepositoryInterface;
use App\Models\Customer;

interface CustomerRepositoryInterface extends CrudRepositoryInterface
{
    public function findByNumIdentification(string $numIdentification, $columns = ['*']): Customer|null;
}
