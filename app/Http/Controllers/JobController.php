<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Session;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        return view('admin.job.index', compact('jobs'));
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
            $job = new Job();
            $job->name = $validatedData['name'];

            $job->created_by = auth()->id();
            $job->updated_by = null;
            $job->deleted_by = null;

            $job->save();

            // Menampilkan SweetAlert
            Session::flash('sukses', 'Yeahh, Job berhasil ditambah!');

            return redirect()->route('jobs.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create job. Please try again.');
        }
    }

    public function show(Job $job)
    {
        //
    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);

        return view('admin.job.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:191',
        ]);

        $job = Job::find($id);
        $job->name = $request->name;
        $job->updated_by = auth()->id();
        $job->save();
        Session::flash('update', 'Job berhasil di update!');
        return redirect()->route('jobs.index');
    }

    public function destroy($id)
    {
        try {
            $job = Job::findOrFail($id);
            $job->deleted_by = auth()->id();

            $job->save();
            $job->delete();

            Session::flash('destroy', 'Job berhasil di hapus!');

            return redirect()->route('jobs.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete job. Please try again.');
        }
    }
}
