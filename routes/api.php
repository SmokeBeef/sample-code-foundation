<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenyewaanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// admin Auth Route
Route::post("/admin/login", [AuthController::class, "login"]);
Route::get("/admin/refresh", [AuthController::class, "refreshToken"])->middleware('jwt');
Route::delete("/admin/logout", [AuthController::class, "logout"])->middleware('jwt');

// Admin Route
Route::post("/admin", [AdminController::class, "store"]);
Route::get("/admin", [AdminController::class, "findAll"]);

// Route Using Token 
Route::middleware("jwt")->group(function () {
    
    // admin route
    Route::delete("admin/{id}", [AdminController::class, "destroy"]);
    
    // kategori route
    Route::post("/kategori", [KategoriController::class, "store"]);
    Route::get("/kategori", [KategoriController::class, "findAll"]);
    Route::get("/kategori/{id}/alat", [KategoriController::class, "findByIdFull"]);
    Route::get("/kategori/alat", [KategoriController::class, "findAllAlat"]);
    Route::put("/kategori/{id}", [KategoriController::class, "update"]);
    Route::delete("/kategori/{id}", [KategoriController::class, "destroy"]);
    
    // alat route
    Route::post("/alat", [AlatController::class, "store"]);
    Route::get("/alat", [AlatController::class, "findAll"]);
    Route::get("/alat/{id}", [AlatController::class, "findById"]);
    Route::put("/alat/{id}", [AlatController::class, "update"]);
    Route::delete("/alat/{id}", [AlatController::class, "destroy"]);

    // pelanggan route
    Route::post("/pelanggan", [PelangganController::class, "store"]);
    Route::get("/pelanggan", [PelangganController::class, "findAll"]);
    Route::get("/pelanggan/{id}", [PelangganController::class, "findById"]);
    Route::put("/pelanggan/{id}", [PelangganController::class, "update"]);
    Route::delete("/pelanggan/{id}", [PelangganController::class, "destroy"]);

    // penyewaan route
    Route::post("/penyewaan", [PenyewaanController::class, "store"]);
    Route::get("/penyewaan", [PenyewaanController::class, "findAll"]);
    Route::get("/penyewaan/{id}", [PenyewaanController::class, "findById"]);
    Route::patch("/penyewaan/{id}", [PenyewaanController::class, "update"]);
    Route::delete("/penyewaan/{id}", [PenyewaanController::class, "destroy"]);
});