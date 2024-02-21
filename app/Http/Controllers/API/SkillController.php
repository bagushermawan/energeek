<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $skills = Skill::all();
        return response()->json(['skills' => $skills], 200);
    }

    public function trashed()
    {
        $skill = Skill::withTrashed()->get();
        return response()->json(['skill' => $skill], 200);
    }

    public function show($id)
    {
        $skill = Skill::findOrFail($id);
        return response()->json($skill);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);


        $skill = Skill::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['skill' => $skill, 'message' => 'Skill berhasil dibuat'], 201);
    }

    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
        ]);

        $skill->update([
            'name' => $request->name,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['skill' => $skill, 'message' => 'Skill berhasil diperbarui'], 200);
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->deleted_by = auth()->id();
        $skill->save();
        $skill->delete();
        return response()->json(['message' => 'Skill deleted successfully']);
    }
}
