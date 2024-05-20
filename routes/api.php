<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PlanController;



Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);




Route::group([
    "middleware" => ['auth:sanctum']
], function () {


    // logout
    Route::post("logout", [ApiController::class, "logout"]);

    // Plan Routes
    Route::post("plan/create", [PlanController::class, "create_plan"]);
    Route::get("plan/get", [PlanController::class, "get_plan"]);
    Route::post("plan/delete", [PlanController::class, "delete_plan"]);
    Route::post("plan/update", [PlanController::class, "update_plan"]);

    // admin routes
    Route::post("customer/profile", [ApiController::class, "customer_profile"]);
    Route::post("inspector/profile", [ApiController::class, "inspector_profile"]);
    Route::get("order/get", [ApiController::class, "get_order"]);

});