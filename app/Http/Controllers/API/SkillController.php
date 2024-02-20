<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();
        return response()->json(['skills' => $skills], 200);
    }

    public function show($id)
    {
        $skill = Skill::findOrFail($id);
        return response()->json($skill);
    }

    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);
        $skill->update($request->all());
        return response()->json(['skill' => $skill, 'message' => 'Skill berhasil diperbarui'], 201);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);


        $skill = Skill::create([
            'name' => $request->name,
        ]);

        return response()->json(['skill' => $skill, 'message' => 'Skill berhasil dibuat'], 201);
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();
        return response()->json(['message' => 'Skill deleted successfully']);
    }
}
