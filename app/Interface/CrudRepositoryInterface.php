<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Model;

interface CrudRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, string | int $id);

    public function delete(Model $model);

    public function find(string | int $id);
}
