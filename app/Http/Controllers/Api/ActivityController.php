<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::all();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => ActivityResource::collection($activities),
        ]);
        // return
        // new ActivityResource('Success', 'Success', $activities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'email'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = Activity::create([
            'title'     => $request->title,
            'email'   => $request->email,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => new ActivityResource($data),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Activity with ID ' . $id . ' Not Found',
            ]);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => new ActivityResource($activity),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Activity with ID ' . $id . ' Not Found',
            ]);
        }
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = $activity->update([
            'title'     => $request->title,
        ]);

        // dd($data);

        return response()->json([
            'status' => 'Success',
            'message' => 'Success',
            'data' => new ActivityResource($activity),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Activity with ID ' . $id . ' Not Found',
            ]);
        }
        $activity->delete();
    }
}
