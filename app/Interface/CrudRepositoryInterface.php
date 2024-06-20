<?php

namespace App\Interface;


interface CrudRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, string | int $id);

    public function delete(string | int $id);

    public function find(string | int $id);
}
