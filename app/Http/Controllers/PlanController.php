<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::all();
        return PlanResource::collection($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        $planRequest = $request->all();
        $plan = new Plan();
        $plan->fill($planRequest);
        $saved = $plan->save();

        if ($saved) return new PlanResource($plan);

        return response()->json(['message' => 'An Error occurred'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {

        return new PlanResource($plan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->fill($request->all());
        $updated = $plan->update();
        if ($updated) return new PlanResource($plan);

        return response()->json(['message' => 'An Error occurred'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->json(['message' => 'Plan deleted succesfully'], 200);
    }
}
