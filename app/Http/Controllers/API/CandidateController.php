<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Job;

class CandidateController extends Controller
{
    public function index()
    {
        // Mengambil semua data candidate beserta relasi job dan skills, serta hanya mengambil kolom yang diperlukan
        $candidates = Candidate::with(['job' => function ($query) {
            // Hanya mengambil kolom yang diperlukan dari relasi job (misalnya, 'id' dan 'name')
            $query->select('id', 'name');
        }])->get();

        // Mengembalikan data candidate beserta nama job dalam respons JSON
        return response()->json(['candidates' => $candidates], 200);
    }
}
