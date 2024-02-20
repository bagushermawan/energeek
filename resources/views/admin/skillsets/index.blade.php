@extends('admin.layouts.master')
@section('page-heading', 'Skill')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List Skill Sets</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @php
                            $colors = ['primary', 'info', 'warning', 'danger']; // Warna yang tersedia
                        @endphp

                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Candidate</th>
                                    <th>Kemampuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skillSets->groupBy('candidate_id') as $candidateId => $sets)
                                    @php
                                        $candidate = $sets->first()->candidate;
                                        $skills = $sets->pluck('skill.name')->toArray();
                                    @endphp
                                    <tr>
                                        <td>{{ $candidate->name }}</td>
                                        <td>
                                            @foreach ($skills as $skill)
                                                @php
                                                    $color = $colors[array_rand($colors)]; // Pilih warna acak
                                                @endphp
                                                <span class="badge badge-{{ $color }}">{{ $skill }}</span>
                                            @endforeach
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
    @include('admin.skill.alert')
@endpush
