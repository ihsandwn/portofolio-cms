<?php

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

// Public API routes (no authentication required)
Route::prefix('mvp')->group(function () {
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'service' => 'CMS Portfolio API',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
});

// Protected API routes (require Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User info endpoint
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
        ]);
    });

    // Token validation endpoint
    Route::get('/validate-token', function (Request $request) {
        return response()->json([
            'valid' => true,
            'user' => $request->user()->only(['id', 'name', 'email']),
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    // Create new Sanctum token for access
    Route::post('/mvp/token', function (Request $request) {
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        $token = $request->user()->createToken($request->device_name, ['mvp-access']);

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $request->user()->only(['id', 'name', 'email']),
        ]);
    });

    // Revoke all MVP tokens
    Route::delete('/mvp/tokens', function (Request $request) {
        $request->user()->tokens()->where('name', 'like', '%mvp%')->delete();

        return response()->json([
            'message' => 'All MVP tokens revoked successfully',
        ]);
    });
});
