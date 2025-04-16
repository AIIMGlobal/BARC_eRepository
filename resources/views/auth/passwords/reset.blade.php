<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>Reset Password | {{ $global_setting->title }}</title>

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

        <style>
            .fxt-form-content {
                position: relative;
                padding: 20px;
                border-radius: 10px;
                overflow: hidden;
            }

            .fxt-form-content .border {
                position: absolute;
                height: 5px;
                width: 50%;
                /* background: linear-gradient(90deg, #FF4500, #FFA500); */
                background: linear-gradient(315deg, #03a9f4, #ff0058);
                box-shadow: 0 0 10px rgba(30, 144, 255, 0.8);
                animation: borderMove 3s linear infinite;
            }

            .fxt-form-content .border-top {
                top: 0;
                left: 50%;
                transform-origin: left;
            }

            .fxt-form-content .border-bottom {
                bottom: 0;
                right: 50%;
                transform-origin: right;
                animation: borderMoveReverse 3s linear infinite;
            }

            .fxt-form-content .border-left {
                width: 5px;
                height: 50%;
                left: 0;
                bottom: 50%;
                transform-origin: bottom;
                animation: borderMoveVerticalReverse 3s linear infinite;
            }

            .fxt-form-content .border-right {
                width: 5px;
                height: 50%;
                right: 0;
                top: 50%;
                transform-origin: top;
                animation: borderMoveVertical 3s linear infinite;
            }

            @keyframes borderMove {
                0% { width: 0; left: 50%; }
                50% { width: 50%; left: 50%; }
                66% { width: 50%; left: 50%; }
                100% { width: 0; left: 100%; }
            }

            @keyframes borderMoveReverse {
                0% { width: 0; right: 50%; }
                50% { width: 50%; right: 50%; }
                66% { width: 50%; right: 50%; }
                100% { width: 0; right: 100%; }
            }

            @keyframes borderMoveVertical {
                0% { height: 0; top: 50%; }
                50% { height: 50%; top: 50%; }
                66% { height: 50%; top: 50%; }
                100% { height: 0; top: 100%; }
            }

            @keyframes borderMoveVerticalReverse {
                0% { height: 0; bottom: 50%; }
                50% { height: 50%; bottom: 50%; }
                66% { height: 50%; bottom: 50%; }
                100% { height: 0; bottom: 100%; }
            }
        </style>
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

                        <div class="fxt-login-option">
                            <ul>
                                {{-- <li class="fxt-transformY-50 fxt-transition-delay-6"><a href="{{ route('login.google') }}"><img src="{{ asset('loginAssets/img/google-logo.png') }}" alt="Google Plus" style="max-width: 35px; margin-right: 5px;"> Sign in with Google</a></li> --}}
                                {{-- <li class="fxt-transformY-50 fxt-transition-delay-7"><a href="#">Sing in with Facebook</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="fxt-form-content">
                    <div class="border border-top"></div>
                    <div class="border border-bottom"></div>
                    <div class="border border-left"></div>
                    <div class="border border-right"></div>

                    <div class="fxt-page-switcher">
                        <h2 class="fxt-page-title mr-3">Login</h2>

                        {{-- <ul class="fxt-switcher-wrap">
                            <li><a href="login-31.html" class="fxt-switcher-btn active">Login</a></li>
                            <li><a href="register-31.html" class="fxt-switcher-btn">Register</a></li>
                        </ul> --}}
                    </div>

                    <div class="fxt-main-form">
                        <div class="fxt-inner-wrap">
                            @include('alerts.alert')

                            <form id="resetForm" action="{{ route('resetPassword') }}" method="POST" autocomplete="off">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="email" id="email" class="form-control" name="email" placeholder="Enter Email" value="{{ $email ?? old('email') }}" required="required" readonly>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <input id="password" type="password" class="form-control" name="password" placeholder="Enter New Password (Minimum 8 Digit)" minlength="8" required="required">
                                            <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Re-Enter Password (Minimum 8 Digit)" minlength="8" required="required">
                                            <i toggle="#password_confirmation" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="submit" class="fxt-btn-fill">Reset Password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                $("#resetForm").on("submit", function (e) {
                    e.preventDefault();

                    let submitButton = $("button[type='submit']");

                    submitButton.prop("disabled", true).text("Resetting...");

                    $.ajax({
                        url: "{{ route('resetPassword') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            token: $("input[name='token']").val(),
                            email: $('#email').val(),
                            password: $('#password').val(),
                            password_confirmation: $('#password_confirmation').val()
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: response.success ? "success" : "error",
                                title: response.success ? "Success" : "Failed",
                                text: response.message
                            });

                            if (response.success) {
                                setTimeout(function () {
                                    window.location.href = response.redirect;
                                }, 1000);
                            }
                        },
                        error: function (xhr) {
                            let errorMessage = "An error occurred. Please try again.";

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({ icon: "error", title: "Failed", text: errorMessage });
                        },
                        complete: function () {
                            submitButton.prop("disabled", false).text("Reset Password");
                        }
                    });
                });
            });
        </script>
    </body>
</html>