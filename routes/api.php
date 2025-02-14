<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Models\Task;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('projects', ProjectController::class);
Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::post('/tasks', [TaskController::class, 'store']);

// mengambil data task dengan relasi project
Route::get('/tasks', function () {
    return response()->json(Task::with('project', 'category')->get());
});
// destroy task
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
// category
Route::get('/categories', [CategoryController::class, 'index']);
// project id get
Route::get('/tasks/project/{projectId}', [TaskController::class, 'getTasksByProject']);
// done
Route::patch('/tasks/{task}/done', [TaskController::class, 'markAsDone']);
// get done
Route::get('/tasks/done', [TaskController::class, 'getDoneTasks']);
// put pending
Route::put('/tasks/{task}/pending', [TaskController::class, 'markAsPending']);

