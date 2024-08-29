<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Service\PlanService;
use Exception;

class PlanController extends Controller
{

    public function __construct(protected PlanService $planService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $plans = $this->planService->listAllPlans();
            return PlanResource::collection($plans);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        try {
            $plans = $this->planService->createPlan($request->all());
            return new PlanResource($plans);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {

        try {
            $resource = $this->planService->findPlanById($plan->id);
            return new PlanResource($resource);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], $th->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        try {
            $plans = $this->planService->updatePlan($request->all(), $plan->id);
            return new PlanResource($plans);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        try {
            $this->planService->deletePlan($plan->id);
            return response()->json(['message' => 'Plan deleted succesfully'], 200);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }
}
