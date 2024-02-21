<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $candidates = Candidate::with(['job' => function ($query) {
            $query->select('id', 'name');
        }])->get();

        return response()->json(['candidates' => $candidates], 200);
    }

    public function trashed()
    {
        $candidates = Candidate::with(['job' => function ($query) {
            $query->select('id', 'name');
        }])->withTrashed()->get();

        return response()->json(['candidates' => $candidates], 200);
    }

    public function store(Request $request): JsonResponse
    {
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

            return response()->json(['error' => $errorMessage], 422);
        }

        $candidate = new Candidate();
        $candidate->name = $request->name;
        $candidate->job_id = $request->job_id;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->year = $request->year;
        $candidate->created_by = auth()->id();

        $candidate->save();

        foreach ($request->skill_id as $skillId) {
            $candidate->skills()->attach($skillId);
        }

        return response()->json(['message' => 'Candidate created successfully', 'candidate' => $candidate], 201);
    }

    public function show($id)
    {
        $candidate = Candidate::with(['job' => function ($query) {
            $query->select('id', 'name');
        }])->findOrFail($id);

        return response()->json($candidate);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'job_id' => 'required',
            'email' => 'required|email|unique:candidates,email,' . $id,
            'phone' => 'required|unique:candidates,phone,' . $id,
            'year' => 'required',
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skills,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

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

            return response()->json(['error' => $errorMessage], 422);
        }

        $candidate = Candidate::findOrFail($id);
        $candidate->name = $request->name;
        $candidate->job_id = $request->job_id;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->year = $request->year;

        $candidate->updated_by = auth()->id();
        $candidate->save();

        $candidate->skills()->detach();

        foreach ($request->skill_id as $skillId) {
            $candidate->skills()->attach($skillId);
        }

        return response()->json(['message' => 'Candidate berhasil diupdate'], 200);
    }

    public function destroy($id)
    {
        try {
            $candidate = Candidate::findOrFail($id);
            $candidate->deleted_by = auth()->id();
            $candidate->save();
            $candidate->delete();

            return response()->json(['message' => 'Candidate berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus candidate. Silakan coba lagi.'], 500);
        }
    }
}
