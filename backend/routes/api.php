<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

// Ruta de prueba para verificar que la API está funcionando
Route::get("/test", function() {
    return response()->json(["message" => "API funcionando correctamente"]);
});

// Rutas protegidas (con autenticación)
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/profile", [AuthController::class, "profile"]);
    Route::apiResource("tasks", TaskController::class);
});
