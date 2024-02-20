<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Session;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();
        return view('admin.skill.index', compact('skills'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Membuat objek pekerjaan baru
            $skill = new Skill();
            $skill->name = $validatedData['name'];

            $skill->created_by = auth()->id();
            $skill->updated_by = null;
            $skill->deleted_by = null;

            $skill->save();

            // Menampilkan SweetAlert
            Session::flash('sukses', 'Yeahh, Skill berhasil ditambah!');

            return redirect()->route('skills.index');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->back()->with('error', 'Failed to create skill. Please try again.');
        }
    }

    public function show(Skill $skill)
    {
        //
    }

    public function edit($id)
    {
        // Mengambil data pekerjaan berdasarkan ID
        $skill = Skill::findOrFail($id);

        // Mengembalikan view form edit dengan data pekerjaan yang akan diedit
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
            // Mengambil data pekerjaan berdasarkan ID
            $skill = Skill::findOrFail($id);
            $skill->deleted_by = auth()->id();

            $skill->save();
            $skill->delete();

            Session::flash('destroy', 'Skill berhasil di hapus!');

            return redirect()->route('skills.index');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->back()->with('error', 'Failed to delete skill. Please try again.');
        }
    }
}
