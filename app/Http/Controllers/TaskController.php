<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Task::with('category',  'project')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = Task::create([
            'Title' => $request->Title,
            'Priority' => $request->Priority,
            'Date' => $request->Date,
            'Time' => $request->Time,
            'CategoryID' => $request->CategoryID,
            'ProjectID' => $request->ProjectID,
        ]);

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($task)
    {
        return response()->json($task->load('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Task $task)
    {
        $task->update($request->validate([
            'Title' => 'required|string|max:255',
            'Date' => 'required|date',
            'Time' => 'required',
            'Priority' => 'required|in:low,medium,high',
            'ProjectID' => 'required|exists:projects,id',
        ]));

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 204);
    }

    public function getTasksByProject($projectId)
    {
        $tasks = Task::with('category', 'project')
        ->where('ProjectID', $projectId)
        ->where('status', 'pending')
        ->get();

    return response()->json($tasks);
    }

    public function markAsDone(Task $task)
    {
        $task->update([
            'status' => 'done',
            'CompletedAt' => now(),
        ]);
        return response()->json(['message' => 'Task marked as done']);
    }

    public function getDoneTasks()
    {
        $tasks = Task::with('category')
        ->where('status', 'done')
        ->get();

        return response()->json($tasks);
    }

    public function markAsPending(Task $task)
    {
        $task->update([
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Task moved back to pending!',
            'task' => $task
        ]);
    }
}   
