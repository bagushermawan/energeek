@extends('layouts.master')
@section('title', 'Bagus Hermawan')
@push('page-css')
    <style>
        .login-box .card,
        .register-box .card {
            margin-bottom: 0;
            background-color: #fff;
            border-radius: 5%;
        }

        .login-card-body,
        .register-card-body {
            background-color: #fff;
            border-top: 0;
            color: #666;
            padding: 20px;
            border-radius: 2%;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #F3F6F9;
            border: none;
            color: #000;
            padding: 0 10px;
            margin-top: 0.31rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #999;
            float: right;
            margin-left: 5px;
            margin-right: -2px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #4e4d4d;
        }

        .form-group {
            margin-bottom: 2rem;
        }
    </style>
@endpush
@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="../../index2.html">
                <img src="{{ asset('energeek.png') }}" style="width: 50%;">
            </a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg" style="font-size: 1.5rem;font-weight:450;">Apply Lamaran</p>

                <form id="lamaranForm">
                    @csrf
                    <label>Nama Lengkap: </label>
                    <div class="form-group">
                        <input id="name" type="text" name="name" class="form-control"
                            placeholder="Cth: Bagus Hermawan">
                    </div>
                    <label>Jabatan: </label>
                    <div class="form-group">
                        <select name="job_id" class="form-control select2" id="job_id">
                            <option value="">Pilih Jabatan</option> <!-- Opsi default kosong -->
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}">{{ $job->name }}</option>
                                <!-- Menambahkan opsi untuk setiap pekerjaan -->
                            @endforeach
                        </select>
                    </div>
                    <label>Telepon: </label>
                    <div class="form-group">
                        <input id="phone" type="number" name="phone" class="form-control"
                            placeholder="Cth: 081547223631">
                    </div>
                    <label>Email: </label>
                    <div class="form-group">
                        <input id="email" type="email" name="email" class="form-control"
                            placeholder="Cth: bagusherma123@gmail.com">
                    </div>
                    <label>Tahun Lahir: </label>
                    <div class="form-group">
                        <input type="text" id="year" class="form-control" name="year">
                    </div>
                    <label>Skill set: </label>
                    <div class="form-group">
                        <select class="form-control select2multi" multiple="multiple" data-placeholder="Pilih Skill"
                            id ="skill_id" name="skill_id[]">
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                <!-- Menambahkan opsi untuk setiap pekerjaan -->
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary btn-block"
                            style="background:#F64E60;border:none; ">Apply</button>
                    </div>
                </form>
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
    <script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
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
                $(this).datepicker('clearDates');
            });
        })
    </script>
    <script>
        $(function() {
            $('#lamaranForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ route('candidates.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: "Lamaran berhasil dikirim!",
                            showConfirmButton: true,
                            confirmButtonText: 'Selesai',
                            confirmButtonColor: '#1BC5BD',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('candidate.lamaran') }}";
                            }
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        var errorMessage = xhr.responseJSON.error;

                        Swal.fire({
                            icon: 'warning',
                            title: 'Terjadi Kesalahan!',
                            text: errorMessage,
                            confirmButtonText: 'Baiklah',
                            confirmButtonColor: '#F64E60',
                        });
                    }
                });
            });
            $('#lamaranForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    job_id: {
                        required: true,
                        minlength: 1
                    },
                    phone: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    year: {
                        required: true
                    },
                    skill_id: {
                        required: true,
                        minlength: 1
                    },
                },
                messages: {
                    name: {
                        required: "Masukkan nama lengkap anda",
                        minlength: "Masukkan nama lengkap dengan benar"
                    },
                    email: {
                        required: "Silakan masukkan alamat email",
                        email: "Email tidak sesuai, sertakan @ pada alamat email "
                    },
                    skill_id: {
                        required: "Silakan masukkan skill",
                        minlength: "Pilih minimal 1 skill "
                    },
                    job_id: {
                        required: "Silakan masukkan jabatan",
                    },
                    year: "Masukkan tahun lahir anda",
                    phone: "Masukkan nomor telepon",
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>

@endpush
