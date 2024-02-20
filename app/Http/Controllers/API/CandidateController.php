<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with(['job' => function ($query) {
            $query->select('id', 'name');
        }])->get();

        // Mengembalikan data candidate beserta nama job dalam respons JSON
        return response()->json(['candidates' => $candidates], 200);
    }

    public function store(Request $request): JsonResponse
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

            // Menampilkan respons JSON dengan pesan kesalahan
            return response()->json(['error' => $errorMessage], 422);
        }

        // Jika validasi berhasil, lanjutkan menyimpan data
        $candidate = new Candidate();
        $candidate->name = $request->name;
        $candidate->job_id = $request->job_id;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->year = $request->year;

        $candidate->save();

        foreach ($request->skill_id as $skillId) {
            $candidate->skills()->attach($skillId);
        }

        // Mengembalikan respons JSON untuk data candidate yang berhasil disimpan
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
        // Validasi data yang diterima dari request
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

            // Menampilkan pesan kesalahan dalam respons JSON
            return response()->json(['error' => $errorMessage], 422);
        }

        // Jika validasi berhasil, lanjutkan menyimpan data
        $candidate = Candidate::findOrFail($id);
        $candidate->name = $request->name;
        $candidate->job_id = $request->job_id;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->year = $request->year;

        $candidate->updated_by = auth()->id();
        $candidate->save();

        // Detach semua skill yang terhubung dengan kandidat
        $candidate->skills()->detach();

        // Attach kembali skill yang baru dipilih
        foreach ($request->skill_id as $skillId) {
            $candidate->skills()->attach($skillId);
        }

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Candidate berhasil diupdate'], 200);
    }

    public function destroy($id)
    {
        try {
            $candidate = Candidate::findOrFail($id);
            $candidate->delete();

            // Memberikan respons JSON sukses
            return response()->json(['message' => 'Candidate berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Memberikan respons JSON gagal jika terjadi kesalahan
            return response()->json(['error' => 'Gagal menghapus candidate. Silakan coba lagi.'], 500);
        }
    }
}
