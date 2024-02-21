<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $jobs = Job::all();
        return response()->json(['jobs' => $jobs], 200);
    }

    public function trashed()
    {
        $jobs = Job::withTrashed()->get();
        return response()->json(['jobs' => $jobs], 200);
    }

    public function show($id)
    {
        $job = Job::with('creator')->findOrFail($id);
        $job->created_by_name = $job->creator->name;

        return response()->json($job);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);


        $job = Job::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['job' => $job, 'message' => 'Job berhasil dibuat'], 201);
    }

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
        ]);

        $job->update([
            'name' => $request->name,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['job' => $job, 'message' => 'Job berhasil diperbarui'], 200);
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->deleted_by = auth()->id();
        $job->save();
        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
}
