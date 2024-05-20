<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PlanController extends Controller
{
    public function create_plan(Request $request){

        $get_res = $request->all();

        $data = [
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'required|string',
            'features'    => 'required|array'
        ];

        $validatePlan = Validator::make($get_res, $data);

        if ($validatePlan->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatePlan->errors()],
                 400);
        }

        Plan::create([
            'name'         => $get_res['name'],
            'price'        => $get_res['price'],
            'description'  => $get_res['description'],
            'features'     => json_encode($get_res['features'])
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Plan added successfully'
        ], 200);
    }

    public function get_plan(Request $request){
        // Fetch all plans from the database
        $plans = Plan::all();

        // Check if any plans were found
        if ($plans->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No plans found'
            ], 404);
        }

        // Decode features field for each plan
        $decodedPlans = $plans->map(function ($plan) {
            $plan->features = json_decode($plan->features);
            return $plan;
        });

        // If plans are found, return them with decoded features
        return response()->json([
            'status' => true,
            'message' => 'Plans retrieved successfully',
            'data' => $decodedPlans
        ], 200);
    }

    public function delete_plan(Request $request){
       
        // Validate incoming request data
        $request->validate([
            'id' => 'required|integer'
        ]);

        // Find the plan by ID
        $plan = Plan::find($request->id);
        
        // Check if the plan exists
        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Plan not found'
            ], 404);
        }

        // Delete the plan
        $plan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Plan deleted successfully'
        ], 200);

    }

    public function update_plan(Request $request){
        // Validate incoming request data
        $request->validate([
            'id'          => 'required|integer',
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'required|string',
            'features'    => 'required|array'
        ]);

        // Find the plan by ID
        $plan = Plan::find($request->id);

        // Check if the plan exists
        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Plan not found'
            ], 404);
        }

        // Update plan fields
        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->description = $request->description;
        $plan->features = json_encode($request->features); // Encode features array to JSON
        $plan->save();

        return response()->json([
            'status' => true,
            'message' => 'Plan updated successfully'
        ], 200);
    }
}
