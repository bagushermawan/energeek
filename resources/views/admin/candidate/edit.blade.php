@extends('admin.layouts.master')
@section('page-heading', 'JOB')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card card-primary">
                    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-3">
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap:</label>
                            <input type="text" id="name" name="name" value="{{ $candidate->name }}" autofocus
                                class="mt-1 p-2 block w-full border rounded-md">
                        </div>
                        <div class="p-3">
                            <label for="job_id" class="block font-medium text-sm text-gray-700">Jabatan:</label>
                            <select name="job_id" id="job_id" class="mt-1 p-2 block w-full border rounded-md select2">
                                @foreach ($jobs as $job)
                                    <option value="{{ $job->id }}"
                                        {{ $candidate->job_id == $job->id ? 'selected' : '' }}>
                                        {{ $job->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-3">
                            <label for="phone" class="block font-medium text-sm text-gray-700">Telepon:</label>
                            <input type="text" id="phone" name="phone" value="{{ $candidate->phone }}"
                                class="mt-1 p-2 block w-full border rounded-md">
                        </div>
                        <div class="p-3">
                            <label for="email" class="block font-medium text-sm text-gray-700">Email:</label>
                            <input type="email" id="email" name="email" value="{{ $candidate->email }}"
                                class="mt-1 p-2 block w-full border rounded-md">
                        </div>
                        <div class="p-3">
                            <label for="year" class="block font-medium text-sm text-gray-700">Tahun Lahir:</label>
                            <input type="text" id="year" name="year" value="{{ $candidate->year }}"
                                class="mt-1 p-2 block w-full border rounded-md">
                        </div>
                        <div class="p-3">
                            <label for="skill_id" class="block font-medium text-sm text-gray-700">Skill:</label>
                            <select name="skill_id[]" id="skill_id" multiple
                                class="mt-1 p-2 block w-full border rounded-md select2multi">
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}"
                                        {{ in_array($skill->id, $candidate->skills->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $skill->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-script')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script
        src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
    </script>
    <script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $('.select2').select2()
            $('.select2multi').select2()
            $('#year').each(function() {
                $(this).datepicker({
                    autoclose: true,
                    format: " yyyy",
                    viewMode: "years",
                    minViewMode: "years"
                });
                // $(this).datepicker('clearDates');
            });
        })
    </script>
@endpush
