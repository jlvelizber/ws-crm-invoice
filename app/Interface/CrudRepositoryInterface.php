<?php

namespace App\Interface;

use Exception;
use Illuminate\Database\Eloquent\Model;

interface CrudRepositoryInterface
{
    public function all();

    public function create(array $data): Model|Exception;

    public function update(array $data, string|int $id);

    public function delete(string|int $id): bool|Exception;

    public function find(string|int $id): Model|Exception;
}
