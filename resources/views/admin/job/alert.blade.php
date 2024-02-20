@if ($message = Session::get('sukses'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ $message }}',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @elseif ($message = Session::get('update'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ $message }}',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @elseif ($message = Session::get('destroy'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Success!',
                text: '{{ $message }}',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @elseif ($message = Session::get('gagal'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Gagal!',
                text: '{{ $message }}',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
