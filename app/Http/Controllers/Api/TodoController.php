<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activity_group_id = $request->activity_group_id;
        if ($activity_group_id) {
            $todos = Todo::where('activity_group_id', $activity_group_id)->get();
        } else {
            $todos = Todo::all();
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => TodoResource::collection($todos),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'activity_group_id'   => 'required',
            'is_active'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = Todo::create([
            'title'     => $request->title,
            'activity_group_id'   => $request->activity_group_id,
            'is_active' => true,
            'priority' => 'very-high',
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => new TodoResource($data),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::find($id);
        // dd($todo);
        if (!$todo) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Todo with ID ' . $id . ' Not Found',
            ]);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => new TodoResource($todo),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Activity with ID ' . $id . ' Not Found',
            ]);
        }
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'activity_group_id'   => 'required',
            'is_active'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = $todo->update([
            'title'     => $request->title,
            'activity_group_id'   => $request->activity_group_id,
            'is_active' => $request->is_active,
            'priority' => $request->priority,
        ]);

        // dd($data);

        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => new TodoResource($todo),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Todo with ID ' . $id . ' Not Found',
            ]);
        }
        $todo->delete();
    }
}
