<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SkillSet;

class SkillSetController extends Controller
{
    public function index()
    {
        // $skillSets = SkillSet::with(['candidate', 'skill'])->get();
        $skillSets = SkillSet::with(['candidate', 'skill' => function ($query) {
            $query->select('id', 'name');
        }])->get();

        return response()->json(['skillSets' => $skillSets], 200);
    }

    public function show($id)
    {
        $skillSets = SkillSet::with(['candidate', 'skill' => function ($query) {
            $query->select('id', 'name');
        }])->findOrFail($id);

        return response()->json($skillSets);
    }

    public function destroy($id)
    {
        $skill = SkillSet::findOrFail($id);
        $skill->delete();
        return response()->json(['message' => 'Skill Set deleted successfully']);
    }
}
