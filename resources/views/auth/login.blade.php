<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>Login | {{ $global_setting->title }}</title>

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

                        <div class="fxt-login-option">
                            <ul>
                                <li class="fxt-transformY-50 fxt-transition-delay-6"><a href="#"><img src="{{ asset('loginAssets/img/google-logo.png') }}" alt="Google Plus" style="max-width: 35px; margin-right: 5px;"> Sign in with Google</a></li> 
                                {{-- {{ route('login.google') }} --}}
                                
                                {{-- <li class="fxt-transformY-50 fxt-transition-delay-7"><a href="#">Sing in with Facebook</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="fxt-form-content">
                    {{-- <div class="border border-top"></div>
                    <div class="border border-bottom"></div>
                    <div class="border border-left"></div>
                    <div class="border border-right"></div> --}}

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

                            <form id="loginForm" action="{{ route('login') }}" method="POST" autocomplete="off">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="email" id="email" class="form-control" name="email" placeholder="Enter Email" required="required">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <input id="password" type="password" class="form-control" name="password" placeholder="Enter Password" required="required">
                                            <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="fxt-checkbox-wrap">
                                                <div class="fxt-checkbox-box mr-3">
                                                    <input id="checkbox1" type="checkbox">
                                                    <label for="checkbox1" class="ps-4">Remember Me</label>
                                                </div>

                                                <a href="{{ route('forgotPasswordPage') }}" class="fxt-switcher-text">Forgot Password</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="submit" class="fxt-btn-fill">Log in</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="fxt-switcher-description">Don't have an account?<a href="{{ route('register') }}" class="fxt-switcher-text ms-1">Register</a></div>
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
                $("#loginForm").on("submit", function (e) {
                    e.preventDefault();

                    let email = $("#email").val().trim();
                    let password = $("#password").val().trim();
                    let submitButton = $("button[type='submit']");

                    if (email === "" || password === "") {
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: "Email and Password are required!"
                        });

                        return;
                    }

                    submitButton.prop("disabled", true);
                    submitButton.text("Logging in...");

                    $.ajax({
                        url: "{{ route('login') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            email: email,
                            password: password
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: response.message
                                });

                                window.location.href = response.redirect;
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Failed!",
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr) {
                            // toastr.error("An error occurred: "+xhr.responseJSON.message);
                            
                            Swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: xhr.responseJSON.message
                            });
                        },
                        complete: function () {
                            submitButton.prop("disabled", false);
                            submitButton.text("Log in");
                        }
                    });
                });
            });

        </script>
    </body>
</html>