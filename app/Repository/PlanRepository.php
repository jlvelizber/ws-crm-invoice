<?php
namespace App\Repository;

use App\Exceptions\DatabaseException;
use App\Models\Plan;
use App\RepositoryInterface\PlanRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class PlanRepository implements PlanRepositoryInterface
{
    /**
     * Get all Object Models
     */
    public function all(): Collection
    {
        return Plan::all();
    }

    /**
     * Store a new Model
     *
     * @param array $data
     * @return Plan | null
     */
    public function create(array $data): Plan|Exception
    {
        try {
            $model = Plan::create($data);
            return $model;
        } catch (QueryException $e) {
            throw new DatabaseException("No se pudo crear el Plan", $e->getCode());
        }
    }

    /**
     * Update a model
     */
    public function update(array $data, string|int $id): Plan|Exception
    {
        try {
            //code...
            $model = $this->find($id);
            $model->fill($data);
            $model->update();
            $model = $this->find($id);
            return $model;
        } catch (QueryException $e) {
            throw new DatabaseException("No se pudo actualizar el Plan", $e->getCode());
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
            throw new DatabaseException("No se pudo eliminar el Plan", $e->getCode());
        }
    }

    /**
     * Get a Model
     * @param string | int $id
     * @return Plan | null
     */
    public function find(string|int $id): Plan|Exception
    {
        try {
            $model = Plan::findOrFail($id);
            return $model;
        } catch (Exception $th) {
            throw new ModelNotFoundException('Plan no existe');
        }

    }

}
