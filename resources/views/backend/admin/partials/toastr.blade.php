<script>
    $(window).on('load', function() {
        let feather;

        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}', 'Error', {
                closeButton: true,
                positionClass: 'toast-bottom-right',
                progressBar: true,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                timeOut: 5000,
            });
        @endforeach
    @endif
</script>

@if (Session::has('success'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ Session::get('success') }}', 'Success', {
                closeButton: true,
                positionClass: 'toast-bottom-right',
                progressBar: true,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                timeOut: 5000,
            });
        });
    </script>
@endif

@if (Session::has('error'))
    <script>
        $(document).ready(function() {
            toastr.error('{{ Session::get('error') }}', 'Error', {
                closeButton: true,
                positionClass: 'toast-bottom-right',
                progressBar: true,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                timeOut: 5000,
            });
        });
    </script>
@endif

@if (session('warning'))
    <script>
        $(document).ready(function() {
            toastr.warning('{{ Session::get('warning') }}', 'warning', {
                closeButton: true,
                positionClass: 'toast-bottom-right',
                progressBar: true,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                timeOut: 5000,
            });
        });
    </script>
@endif