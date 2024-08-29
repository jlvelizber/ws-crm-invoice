<?php

namespace App\Service;

use App\RepositoryInterface\PlanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class PlanService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected PlanRepositoryInterface $planRepositoryInterface)
    {
    }


    public function listAllPlans(): Collection
    {
        return $this->planRepositoryInterface->all();
    }



    /**
     * Create a new Plan
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model| Exception
     */
    public function createPlan(array $data): Model|Exception
    {
        DB::beginTransaction();
        try {
            $plan = $this->planRepositoryInterface->create($data);
            DB::commit();
            return $plan;
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Find a Plan by Id
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|\Exception
     */
    public function findPlanById(int $id): Model|Exception
    {
        return $this->planRepositoryInterface->find($id);
    }

    /**
     * Update an user Service
     * @param mixed $data
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model|\Exception
     */
    public function updatePlan($data, $id): Model|Exception
    {
        DB::beginTransaction();
        try {
            $plan = $this->planRepositoryInterface->update($data, $id);
            DB::commit();
            return $plan;
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function deletePlan(int $id): void
    {
        DB::beginTransaction();
        try {

            $this->planRepositoryInterface->delete($id);
            DB::commit();
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
