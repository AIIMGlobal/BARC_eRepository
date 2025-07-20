<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>Verification | {{ $global_setting->title }}</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Favicon -->
        @if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}">
        @else
            <link rel="shortcut icon" type="image/x-icon" href="{{ 'https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg' }}">
        @endif

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('loginAssets/css/bootstrap.min.css') }}">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{ asset('loginAssets/css/fontawesome-all.min.css') }}">
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset('loginAssets/font/flaticon.css') }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- Google Web Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('loginAssets/style.css') }}">

        @include('auth.partials.css')
    </head>

    <body>
        <div id="preloader" class="preloader">
            <div class='inner'>
                <div class='line1'></div>
                <div class='line2'></div>
                <div class='line3'></div>
            </div>
        </div>

        <section class="fxt-template-animation fxt-template-layout31">
            <span class="fxt-shape fxt-animation-active"></span>

            <div class="fxt-content-wrap">
                <div class="fxt-heading-content">
                    <div class="fxt-inner-wrap">
                        <div class="fxt-transformY-50 fxt-transition-delay-3">
                            @if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
                                <a href="{{ route('admin.home') }}" class="fxt-logo"><img src="{{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}" alt="Logo" style="max-width: 300px;"></a>
                            @else
                                <a href="{{ route('admin.home') }}" class="fxt-logo"><img src="{{ 'https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg' }}" alt="Logo" style="max-width: 300px;"></a>
                            @endif
                        </div>

                        <div class="fxt-transformY-50 fxt-transition-delay-4">
                            <h1 class="fxt-main-title">{{ $global_setting->title }}</h1>
                        </div>
                    </div>
                </div>

                <div class="fxt-form-content">
                    {{-- <div class="border border-top"></div>
                    <div class="border border-bottom"></div>
                    <div class="border border-left"></div>
                    <div class="border border-right"></div> --}}

                    <div class="fxt-page-switcher">
                        <h2 class="fxt-page-title mr-3">Verification</h2>

                        <ul class="fxt-switcher-wrap">
                            <li><a href="{{ route('about') }}" class="fxt-switcher-btn active">About</a></li>
                            <li><a href="{{ route('contact') }}" class="fxt-switcher-btn">Contact</a></li>
                        </ul>
                    </div>

                    <div class="fxt-main-form">
                        <div class="fxt-inner-wrap text-center" style="max-width: 100%;">
                            @include('alerts.alert')

                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-0">To complete registration, please verify your email. We just sent an verification link to: </p>

                                    <a href="mailto:{{ $user->email }}" style="font-size: 20px; text-decoration: underline;">{{ $user->email }}</a>
                                </div>
                            </div>

                            <div class="fxt-switcher-description mt-5">Didn't receive an email? Please check your spam folder or <a href="javascript:void(0);" data-id="{{ Crypt::encryptString($user->id) }}" class="fxt-switcher-text ms-1" id="resendEmail">Resend Email</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- jquery-->
        <script src="{{ asset('loginAssets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap js -->
        <script src="{{ asset('loginAssets/js/bootstrap.min.js') }}"></script>
        <!-- Imagesloaded js -->
        <script src="{{ asset('loginAssets/js/imagesloaded.pkgd.min.js') }}"></script>
        <!-- Validator js -->
        <script src="{{ asset('loginAssets/js/validator.min.js') }}"></script>
        <!-- Custom Js -->
        <script src="{{ asset('loginAssets/js/main.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).ready(function () {
                $("#resendEmail").on("click", function (e) {
                    e.preventDefault();

                    let userId = $(this).data('id');
                    let resendUrl = "{{ route('resendMail', ':id') }}".replace(':id', userId);

                    $.ajax({
                        url: "{{ route('register.store') }}",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: response.message
                                });

                                window.location.href = response.redirect;
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Failed",
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr) {
                            toastr.error("An error occurred. Please try again.");
                        },
                        complete: function () {
                            submitButton.prop("disabled", false);
                            submitButton.text("Register");
                        }
                    });
                });
            });
        </script>
    </body>
</html>