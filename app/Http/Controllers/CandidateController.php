<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Job;
use App\Models\Skill;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        $skills = Skill::all();
        return view('candidate', compact('jobs', 'skills'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'job_id' => 'required',
            'email' => 'required|email|unique:candidates,email',
            'phone' => 'required|unique:candidates,phone',
            'year' => 'required',
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skills,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            // Membuat pesan kesalahan berdasarkan validasi
            $errorMessage = '';

            if ($errors->has('name')) {
                $errorMessage = 'Masukkan nama lengkap anda.';
            } elseif ($errors->has('job_id')) {
                $errorMessage = 'Silakan masukkan jabatan.';
            } elseif ($errors->has('email')) {
                $errorMessage = 'Email sudah terdaftar.';
            } elseif ($errors->has('phone')) {
                $errorMessage = 'Telepon sudah terdaftar.';
            } elseif ($errors->has('year')) {
                $errorMessage = 'Silakan masukkan tahun lahir.';
            } elseif ($errors->has('skill_id')) {
                $errorMessage = 'Silakan masukkan skill anda.';
            }

            // Menampilkan sweetalert dengan pesan kesalahan
            return response()->json(['error' => $errorMessage], 422);
        }

        // Jika validasi berhasil, lanjutkan menyimpan data
        $candidate = new Candidate();
        $candidate->name = $request->name;
        $candidate->job_id = $request->job_id;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->year = $request->year;

        $candidate->created_by = auth()->id();
        $candidate->updated_by = null;
        $candidate->deleted_by = null;

        $candidate->save();

        foreach ($request->skill_id as $skillId) {
            $candidate->skills()->attach($skillId);
        }

        return redirect()->route('candidates.index');
    }

    public function show(Candidate $candidate)
    {
        //
    }

    public function edit(Candidate $candidate)
    {
        //
    }

    public function update(Request $request, Candidate $candidate)
    {
        //
    }

    public function destroy(Candidate $candidate)
    {
        //
    }
}
