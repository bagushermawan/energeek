<?php

namespace App\Http\Controllers;

use App\Models\SkillSet;
use Illuminate\Http\Request;

class SkillSetsController extends Controller
{
    public function index()
    {
        $skillSets = SkillSet::with(['candidate', 'skill'])->get();

        return view('admin.skillsets.index', compact('skillSets'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(SkillSet $skillSet)
    {
        //
    }

    public function edit(SkillSet $skillSet)
    {
        //
    }

    public function update(Request $request, SkillSet $skillSet)
    {
        //
    }

    public function destroy(SkillSet $skillSet)
    {
        //
    }
}
