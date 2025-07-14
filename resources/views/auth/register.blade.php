<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>Register | {{ $global_setting->title }}</title>

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
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

            .select2-selection__rendered {
                line-height: 50px !important;
            }
            .select2-container .select2-selection--single {
                height: 50px !important;
                border-color: #3DB0437a;
            }
            .select2-selection__arrow {
                height: 50px !important;
            }

            /* Highlight required fields */
            .form-group.required .form-control,
            .form-group.required .select2-container .select2-selection--single {
                border: 2px solid #3DB043; /* Green for required fields */
                background-color: #f0fff0;
            }

            .form-group.required.valid .form-control,
            .form-group.required.valid .select2-container .select2-selection--single {
                border: 2px solid #3DB043; /* Green for valid fields */
                background-color: #f0fff0;
            }

            .form-group.required.invalid .form-control,
            .form-group.required.invalid .select2-container .select2-selection--single {
                border: 2px solid #ff0058; /* Red for invalid fields */
                background-color: #fff5f5;
            }

            .validation-message {
                color: #ff0058;
                font-size: 0.9em;
                margin-top: 5px;
                display: none;
            }

            .validation-message.show {
                display: block;
            }

            /* Eye icon positioning */
            .fxt-template-layout31 .fxt-form-content .form-group .field-icon {
                position: absolute;
                right: 19px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
            }

            .fxt-template-layout31 .fxt-form-content .form-group #password_error.show ~ .field-icon,
            .fxt-template-layout31 .fxt-form-content .form-group #password_confirmation_error.show ~ .field-icon {
                bottom: 0;
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
                    </div>
                </div>

                <div class="fxt-form-content">
                    <div class="fxt-page-switcher">
                        <h2 class="fxt-page-title mr-3">Register</h2>
                    </div>

                    <div class="fxt-main-form">
                        <div class="fxt-inner-wrap" style="max-width: 100%;">
                            @include('alerts.alert')

                            <form id="registerForm" action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" id="name_en" class="form-control" name="name_en" placeholder="Enter Full Name">
                                            <div class="validation-message" id="name_en_error">Full Name is required</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group required">
                                            <select name="role_id" id="role_id" class="form-control select2" required>
                                                <option value="">--Select User Type--</option>
                                                <option value="4">Employee</option>
                                                <option value="5">Others</option>
                                            </select>
                                            <div class="validation-message" id="role_id_error">User Type is required</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <select name="user_category_id" id="user_category_id" class="form-control select2">
                                                <option value="">--Select User Service Type--</option>
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="validation-message" id="user_category_id_error">User Service Type is required</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group" id="office_id_group">
                                            <select name="office_id" id="office_id" class="form-control select2">
                                                <option value="">--Select Organization--</option>
                                                @foreach ($orgs as $org)
                                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="validation-message" id="office_id_error">Organization is required</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group" id="designation_id_group">
                                            <select name="designation_id" id="designation_id" class="form-control select2">
                                                <option value="">--Select Designation--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                @endforeach
                                                <option value="1000">Other</option>
                                            </select>
                                            <div class="validation-message" id="designation_id_error">Designation is required</div>
                                        </div>

                                        <div class="form-group" id="other_designation_group" style="display: none;">
                                            <input type="text" name="other_designation" id="other_designation" class="form-control" placeholder="Enter new designation">
                                            <div class="validation-message" id="other_designation_error">New Designation is required</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="email" id="email" class="form-control" name="email" placeholder="Enter Email">
                                            <div class="validation-message" id="email_error">Please enter a valid email address</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" id="mobile" class="form-control" name="mobile" placeholder="Enter Mobile No." maxlength="14">
                                            <div class="validation-message" id="mobile_error">Mobile number must be 11 digits</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <input id="password" type="password" class="form-control" name="password" placeholder="Enter Password (Minimum 8 Digit)" minlength="8">
                                                <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                                <div class="validation-message" id="password_error">Password must be at least 8 characters</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Re-Enter Password (Minimum 8 Digit)" minlength="8">
                                                <i toggle="#password_confirmation" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                                <div class="validation-message" id="password_confirmation_error">Passwords do not match</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="file" id="image" class="form-control" name="image">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="submit" class="fxt-btn-fill">Register</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="fxt-switcher-description">Already have an account?<a href="{{ url('/') }}" class="fxt-switcher-text ms-1">Login</a></div>
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

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Custom Js -->
        <script src="{{ asset('loginAssets/js/main.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).ready(function () {
                $('.select2').select2();

                // Object to track whether validation messages should be shown
                const validationState = {
                    name_en: false,
                    role_id: false,
                    user_category_id: false,
                    office_id: false,
                    designation_id: false,
                    other_designation: false,
                    email: false,
                    mobile: false,
                    password: false,
                    password_confirmation: false
                };

                // Validation functions
                function validateName() {
                    const name = $('#name_en').val().trim();
                    const errorElement = $('#name_en_error');
                    const formGroup = $('#name_en').closest('.form-group');
                    if (name === '') {
                        if (validationState.name_en) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.name_en = false;
                        return true;
                    }
                }

                function validateRoleId() {
                    const roleId = $('#role_id').val();
                    const errorElement = $('#role_id_error');
                    const formGroup = $('#role_id').closest('.form-group');
                    if (roleId === '') {
                        if (validationState.role_id) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.role_id = false;
                        return true;
                    }
                }

                function validateUserCategoryId() {
                    const categoryId = $('#user_category_id').val();
                    const errorElement = $('#user_category_id_error');
                    const formGroup = $('#user_category_id').closest('.form-group');
                    if (categoryId === '') {
                        if (validationState.user_category_id) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.user_category_id = false;
                        return true;
                    }
                }

                function validateOfficeId() {
                    const roleId = $('#role_id').val();
                    const officeId = $('#office_id').val();
                    const errorElement = $('#office_id_error');
                    const formGroup = $('#office_id_group');
                    if (roleId === '4' && officeId === '') {
                        if (validationState.office_id) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.office_id = false;
                        return true;
                    }
                }

                function validateDesignationId() {
                    const roleId = $('#role_id').val();
                    const designationId = $('#designation_id').val();
                    const errorElement = $('#designation_id_error');
                    const formGroup = $('#designation_id_group');
                    if (roleId === '4' && designationId === '') {
                        if (validationState.designation_id) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.designation_id = false;
                        return true;
                    }
                }

                function validateOtherDesignation() {
                    const roleId = $('#role_id').val();
                    const designationId = $('#designation_id').val();
                    const otherDesignation = $('#other_designation').val().trim();
                    const errorElement = $('#other_designation_error');
                    const formGroup = $('#other_designation_group');
                    if (roleId === '4' && designationId === '1000' && otherDesignation === '') {
                        if (validationState.other_designation) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.other_designation = false;
                        return true;
                    }
                }

                function validateEmail() {
                    const email = $('#email').val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const errorElement = $('#email_error');
                    const formGroup = $('#email').closest('.form-group');
                    if (!emailRegex.test(email)) {
                        if (validationState.email) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.email = false;
                        return true;
                    }
                }

                function validateMobile() {
                    const mobile = $('#mobile').val().trim();
                    const mobileRegex = /^\d{11}$/;
                    const errorElement = $('#mobile_error');
                    const formGroup = $('#mobile').closest('.form-group');
                    if (!mobileRegex.test(mobile)) {
                        if (validationState.mobile) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.mobile = false;
                        return true;
                    }
                }

                function validatePassword() {
                    const password = $('#password').val();
                    const errorElement = $('#password_error');
                    const formGroup = $('#password').closest('.form-group');
                    if (password.length < 8) {
                        if (validationState.password) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.password = false;
                        return true;
                    }
                }

                function validatePasswordConfirmation() {
                    const password = $('#password').val();
                    const passwordConfirmation = $('#password_confirmation').val();
                    const errorElement = $('#password_confirmation_error');
                    const formGroup = $('#password_confirmation').closest('.form-group');
                    if (password !== passwordConfirmation || passwordConfirmation.length < 8) {
                        if (validationState.password_confirmation) {
                            errorElement.addClass('show');
                            formGroup.addClass('invalid').removeClass('valid');
                        }
                        return false;
                    } else {
                        errorElement.removeClass('show');
                        formGroup.addClass('valid').removeClass('invalid');
                        validationState.password_confirmation = false;
                        return true;
                    }
                }

                // Toggle required fields based on role
                function toggleRequiredFields() {
                    const roleId = $('#role_id').val();
                    const fields = [
                        { id: 'name_en', group: $('#name_en').closest('.form-group') },
                        { id: 'user_category_id', group: $('#user_category_id').closest('.form-group') },
                        { id: 'office_id', group: $('#office_id_group') },
                        { id: 'designation_id', group: $('#designation_id_group') },
                        { id: 'other_designation', group: $('#other_designation_group') },
                        { id: 'email', group: $('#email').closest('.form-group') },
                        { id: 'mobile', group: $('#mobile').closest('.form-group') },
                        { id: 'password', group: $('#password').closest('.form-group') },
                        { id: 'password_confirmation', group: $('#password_confirmation').closest('.form-group') }
                    ];

                    if (roleId === '4') { // Employee
                        fields.forEach(field => {
                            field.group.addClass('required');
                            $(`#${field.id}`).prop('required', true);
                            if (!field.group.hasClass('valid') && !field.group.hasClass('invalid')) {
                                field.group.addClass('required');
                            }
                        });
                        if ($('#designation_id').val() === '1000') {
                            $('#other_designation_group').addClass('required');
                            $('#other_designation').prop('required', true);
                        } else {
                            $('#other_designation_group').removeClass('required');
                            $('#other_designation').removeAttr('required').val('');
                            $('#other_designation_error').removeClass('show');
                            $('#other_designation_group').addClass('valid').removeClass('invalid');
                            validationState.other_designation = false;
                        }
                    } else if (roleId === '5') { // Others
                        fields.forEach(field => {
                            if (field.id !== 'name_en' && field.id !== 'email' && field.id !== 'mobile' && field.id !== 'password' && field.id !== 'password_confirmation') {
                                field.group.removeClass('required');
                                $(`#${field.id}`).removeAttr('required');
                                $(`#${field.id}_error`).removeClass('show');
                                field.group.addClass('valid').removeClass('invalid');
                                validationState[field.id] = false;
                            } else {
                                field.group.addClass('required');
                                $(`#${field.id}`).prop('required', true);
                                if (!field.group.hasClass('valid') && !field.group.hasClass('invalid')) {
                                    field.group.addClass('required');
                                }
                            }
                        });
                        $('#other_designation_group').removeClass('required');
                        $('#other_designation').removeAttr('required').val('');
                        $('#other_designation_error').removeClass('show');
                        $('#other_designation_group').addClass('valid').removeClass('invalid');
                        validationState.other_designation = false;
                    } else {
                        fields.forEach(field => {
                            if (field.id !== 'role_id') {
                                field.group.removeClass('required');
                                $(`#${field.id}`).removeAttr('required');
                                $(`#${field.id}_error`).removeClass('show');
                                field.group.removeClass('valid').removeClass('invalid');
                                validationState[field.id] = false;
                            }
                        });
                    }
                    validateOfficeId();
                    validateDesignationId();
                    validateOtherDesignation();
                }

                // Event listeners for real-time validation on key input/change
                $('#name_en').on('input', function() {
                    validationState.name_en = true;
                    validateName();
                });
                $('#role_id').on('change', function() {
                    validationState.role_id = true;
                    toggleRequiredFields();
                    validateRoleId();
                });
                $('#user_category_id').on('change', function() {
                    validationState.user_category_id = true;
                    validateUserCategoryId();
                });
                $('#office_id').on('change', function() {
                    validationState.office_id = true;
                    validateOfficeId();
                });
                $('#designation_id').on('change', function() {
                    validationState.designation_id = true;
                    validateDesignationId();
                    if ($(this).val() === '1000' && $('#role_id').val() === '4') {
                        $('#other_designation_group').show().addClass('required');
                        $('#other_designation').prop('required', true);
                        if (!validationState.other_designation) {
                            $('#other_designation_group').addClass('required');
                        }
                        validateOtherDesignation();
                    } else {
                        $('#other_designation_group').hide().removeClass('required');
                        $('#other_designation').removeAttr('required').val('');
                        $('#other_designation_error').removeClass('show');
                        $('#other_designation_group').addClass('valid').removeClass('invalid');
                        validationState.other_designation = false;
                    }
                });
                $('#other_designation').on('input', function() {
                    validationState.other_designation = true;
                    validateOtherDesignation();
                });
                $('#email').on('input', function() {
                    validationState.email = true;
                    validateEmail();
                });
                $('#mobile').on('input', function() {
                    validationState.mobile = true;
                    validateMobile();
                });
                $('#password').on('input', function() {
                    validationState.password = true;
                    validatePassword();
                    validatePasswordConfirmation();
                });
                $('#password_confirmation').on('input', function() {
                    validationState.password_confirmation = true;
                    validatePasswordConfirmation();
                    $(this).siblings('.field-icon').addClass('active');
                });
                $('#password_confirmation').on('blur', function() {
                    $(this).siblings('.field-icon').removeClass('active');
                });

                $('.toggle-password').on('click', function() {
                    const input = $('.toggle-password');

                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });

                // Form submission
                $("#registerForm").on("submit", function (e) {
                    e.preventDefault();

                    // Validate all fields
                    const isValid = validateName() &&
                                   validateRoleId() &&
                                   validateUserCategoryId() &&
                                   validateOfficeId() &&
                                   validateDesignationId() &&
                                   validateOtherDesignation() &&
                                   validateEmail() &&
                                   validateMobile() &&
                                   validatePassword() &&
                                   validatePasswordConfirmation();

                    if (!isValid) {
                        toastr.error("Please fix all validation errors before submitting.");
                        // Show all validation messages for invalid fields
                        Object.keys(validationState).forEach(key => {
                            validationState[key] = true;
                        });
                        validateName();
                        validateRoleId();
                        validateUserCategoryId();
                        validateOfficeId();
                        validateDesignationId();
                        validateOtherDesignation();
                        validateEmail();
                        validateMobile();
                        validatePassword();
                        validatePasswordConfirmation();
                        return;
                    }

                    let formData = new FormData(this);
                    let submitButton = $("button[type='submit']");

                    submitButton.prop("disabled", true);
                    submitButton.text("Registering...");

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
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Failed",
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.message;
                                Swal.fire({
                                    icon: "error",
                                    title: "Validation Error",
                                    text: errors
                                });
                            } else {
                                toastr.error("An error occurred. Please try again.");
                            }
                        },
                        complete: function () {
                            submitButton.prop("disabled", false);
                            submitButton.text("Register");
                        }
                    });
                });

                // Initial setup: only role_id is required and highlighted
                toggleRequiredFields();
            });
        </script>
    </body>
</html>