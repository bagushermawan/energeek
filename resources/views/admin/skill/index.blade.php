@extends('admin.layouts.master')
@section('page-heading', 'Skill')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card card-primary">
                    <form id="jobForm" action="{{ route('skills.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Skill: </label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List Skill</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div>
                            <button id="showWithTrashed" class="btn btn-primary">Show With Trashed</button>
                            <button id="hideWithTrashed" class="btn btn-info">Hide Trashed</button>
                        </div>
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Skill</th>
                                    <th>Created by</th>
                                    <th>Updated by</th>
                                    @if (request()->has('withTrashed') && request()->withTrashed == 'true')
                                        <th>Deleted at</th>
                                        <th>Deleted by</th>
                                    @endif
                                    <th>Action</th>
                                    {{-- <th>Deleted by</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skills as $a)
                                    <tr>
                                        <td>{{ $a->name }}</td>
                                        <td>{{ $a->creator ? $a->creator->name : 'null' }}</td>
                                        <td>{{ $a->updater ? $a->updater->name : 'null' }}</td>
                                        @if (request()->has('withTrashed') && request()->withTrashed == 'true')
                                            <td>{{ $a->deleted_at ? $a->deleted_at : 'null' }}</td>
                                            <td>{{ $a->deleter ? $a->deleter->name : 'null' }}</td>
                                        @endif
                                        <td>
                                            <a href="{{ route('skills.edit', $a->id) }}" class="btn btn-primary">Edit</a>
                                            <form action="{{ route('skills.destroy', $a->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-script')
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });
        });
    </script>
    <script>
        // Event listener untuk tombol "Tampilkan Data Dengan Terhapus"
        document.getElementById('showWithTrashed').addEventListener('click', function() {
            updateUrlParameter('withTrashed', 'true');
        });

        // Event listener untuk tombol "Tampilkan Data Tanpa Terhapus"
        document.getElementById('hideWithTrashed').addEventListener('click', function() {
            updateUrlParameter('withTrashed', 'false');
        });

        // Fungsi untuk memperbarui parameter URL dengan nilai yang diberikan
        function updateUrlParameter(key, value) {
            var url = new URL(window.location.href);
            var params = new URLSearchParams(url.search);
            params.set(key, value);
            url.search = params.toString();
            window.location.href = url.toString();
        }
    </script>
    @include('admin.skill.alert')
@endpush
