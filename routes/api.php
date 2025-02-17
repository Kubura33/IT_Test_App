<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('categories', CategoryController::class)->only(['index', 'show', 'update', 'destroy'])->missing(function (Request $request) {
    return response()->json(['message' => "This resource doesn't exist"], 404);
});
Route::apiResource('products', ProductController::class)->only(['index', 'show', 'update', 'destroy'])->missing(function (Request $request) {
    return response()->json(['message' => "This resource doesn't exist"], 404);
});
Route::get('/export-csv/{category_name}', [ProductController::class, 'exportByCategory'])->missing(function (Request $request) {
    return response()->json(['message' => "Products with this category not found"], 404);
});
