@extends('admin.layouts.master')
@section('page-heading', 'JOB')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card card-primary">
                    <form action="{{ route('skills.update', $skill->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nama Skill: </label>
                            <input type="text" class="form-control" name="name" value="{{ $skill->name }}" autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-script')
@endpush
