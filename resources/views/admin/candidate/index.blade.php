@extends('admin.layouts.master')
@section('page-heading', 'Candidates')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List Candidates</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @php
                            $colors = ['primary', 'info', 'warning', 'danger']; // Warna yang tersedia
                        @endphp
                        <div>
                            <button id="showWithTrashed" class="btn btn-primary">Show With Trashed</button>
                            <button id="hideWithTrashed" class="btn btn-info">Hide Trashed</button>
                        </div>
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <th>Jabatan</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Tahun Lahir</th>
                                    <th>Skills</th>
                                     @if (request()->has('withTrashed') && request()->withTrashed == 'true')
                                        <th>Deleted at</th>
                                        <th>Deleted by</th>
                                    @endif
                                    <th>Action</th>
                                    {{-- <th>Deleted by</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($candidates as $a)
                                    <tr>
                                        <td>{{ $a->name }}</td>
                                        <td>{{ $a->job ? $a->job->name : 'null' }}</td>
                                        <td>{{ $a->phone }}</td>
                                        <td>{{ $a->email }}</td>
                                        <td>{{ $a->year }}</td>
                                        <td>
                                            @foreach ($a->skills as $skill)
                                                @php
                                                    $color = $colors[array_rand($colors)];
                                                @endphp
                                                <span class="badge badge-{{ $color }}">{{ $skill->name }}</span>
                                            @endforeach
                                        </td>
                                        @if (request()->has('withTrashed') && request()->withTrashed == 'true')
                                            <td>{{ $a->deleted_at ? $a->deleted_at : 'null' }}</td>
                                            <td>{{ $a->deleter ? $a->deleter->name : 'null' }}</td>
                                        @endif
                                        <td>
                                            <a href="{{ route('candidates.edit', $a->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('candidates.destroy', $a->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
    @include('admin.candidate.alert')
@endpush
