<?php
namespace App\Repository;

use App\Exceptions\DatabaseException;
use App\RepositoryInterface\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all Object Models
     */
    public function all(): Collection
    {
        return User::all();
    }

    /**
     * Store a new Model
     *
     * @param array $data
     * @return User | null
     */
    public function create(array $data): User|Exception
    {
        try {
            $model = User::create($data);
            return $model;
        } catch (QueryException $e) {
            throw new DatabaseException("No se pudo crear el usuario", $e->getCode());
        }
    }

    /**
     * Update a model
     */
    public function update(array $data, string|int $id): User|Exception
    {
        try {
            //code...
            $model = $this->find($id);
            $model->fill($data);
            $model->update();
            $model = $this->find($id);
            return $model;
        } catch (QueryException $e) {
            throw new DatabaseException("No se pudo actualizar el usuario", $e->getCode());
        }

    }

    /**
     * Delete a model
     */
    public function delete(string|int $id): bool|Exception
    {
        try {
            $this->find($id)->delete();
            return true;
        } catch (QueryException $e) {
            throw new DatabaseException("No se pudo eliminar el usuario", $e->getCode());
        }
    }

    /**
     * Get a Model
     * @param string | int $id
     * @return User | null
     */
    public function find(string|int $id): User|Exception
    {
        try {
            $model = User::findOrFail($id);
            return $model;
        } catch (Exception $th) {
            throw new ModelNotFoundException('Cliente no existe');
        }

    }

}
