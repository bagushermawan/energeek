<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Session;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $jobsQuery = Skill::query();
        if ($request->has('withTrashed') && $request->withTrashed === 'true') {
            $jobsQuery->withTrashed();
        }

        $skills = $jobsQuery->get();

        return view('admin.skill.index', compact('skills'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $skill = new Skill();
            $skill->name = $validatedData['name'];

            $skill->created_by = auth()->id();
            $skill->updated_by = null;
            $skill->deleted_by = null;

            $skill->save();

            Session::flash('sukses', 'Yeahh, Skill berhasil ditambah!');

            return redirect()->route('skills.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create skill. Please try again.');
        }
    }

    public function show(Skill $skill)
    {
        //
    }

    public function edit($id)
    {
        $skill = Skill::findOrFail($id);

        return view('admin.skill.edit', compact('skill'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:191',
        ]);

        $skill = Skill::find($id);
        $skill->name = $request->name;
        $skill->updated_by = auth()->id();
        $skill->save();
        Session::flash('update', 'Skill berhasil di update!');
        return redirect()->route('skills.index');
    }

    public function destroy($id)
    {
        try {
            $skill = Skill::findOrFail($id);
            $skill->deleted_by = auth()->id();

            $skill->save();
            $skill->delete();

            Session::flash('destroy', 'Skill berhasil di hapus!');

            return redirect()->route('skills.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete skill. Please try again.');
        }
    }
}
