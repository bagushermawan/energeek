<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();
        return response()->json(['skills' => $skills], 200);
    }

    public function show($id)
    {
        $job = Skill::findOrFail($id);
        return response()->json($job);
    }

    public function update(Request $request, $id)
    {
        $job = Skill::findOrFail($id);
        $job->update($request->all());
        return response()->json($job);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);


        $job = Skill::create([
            'name' => $request->name,
        ]);

        return response()->json($job, 201);
    }

    public function destroy($id)
    {
        $job = Skill::findOrFail($id);
        $job->delete();
        return response()->json(['message' => 'Skill deleted successfully']);
    }
}
