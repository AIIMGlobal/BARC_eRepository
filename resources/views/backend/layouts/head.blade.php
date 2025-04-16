<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8" />

        <title>@yield('title', ''.($global_setting->title ?? "").'')</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset($global_setting->soft_logo ? ('storage/soft_logo/' . ($global_setting->soft_logo ?? '')) : ('backend-assets/assets/images/favicon.ico')) }}">

        {{-- select2 css --}}
        <link rel="stylesheet" href="{{ asset('backend-assets/assets/css/select2.min.css') }}">

        <link rel="stylesheet" href="{{ asset('backend-assets/assets/libs/glightbox/css/glightbox.min.css') }}">
        
        <!-- Bootstrap Css -->
        <link href="{{ asset('backend-assets/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
        
        <!-- Icons Css -->
        <link href="{{ asset('backend-assets/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- App Css-->
        <link href="{{ asset('backend-assets/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        
        <!-- custom Css-->
        <link href="{{ asset('backend-assets/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" rel="stylesheet" type="text/css" />

        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">

        <!-- Filepond css -->
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

        {{-- toastr css link --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css"/>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <!-- Layout config Js -->
        <script src="{{ asset('backend-assets/assets/js/layout.js') }}"></script>

        <style>
            @font-face {
                font-family: nikosh;
                src: url({{asset('font.Nikosh.ttf')}});
            }

            body {
                font-family: "Merriweather", serif;
                font-optical-sizing: auto;
                font-style: normal;
                font-size: 14px;
                padding: 0;
                margin: 0;
                background: #F5F5F5;
                color: #051A1A;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: "Merriweather", serif;
                font-optical-sizing: auto;
                color: #051A1A;
                font-weight: 700;
            }

            .app-menu {
                z-index: 1;
                /* background: linear-gradient(45deg, #3959d7cf,#d77439e5) !important; */
                background: linear-gradient(90deg, #0F2027, #203A43, #2C5364) !important;
                overflow: hidden;
            }
            .app-menu.navbar-menu::after {
                content: '';
                position: absolute;
                top: -180px;
                width: 180px;
                background: none !important;
                left: -124px;
                height: 1000px;
                border-radius: 0;
                transform: rotate3d(1, 1, 1, 45deg);
                z-index: -10;
            }

            #page-topbar {
                background: linear-gradient(90deg, #0F2027, #203A43, #2C5364) !important;
                /* background: url({{asset('assets/images/backgound_image.png')}}); */
                margin: 8px;
                border-radius: 10px;
                /* background: url('{{asset("frontend/img/background_animation.gif")}}'); */
                /* background: url('{{asset("frontend/img/gffiphy.gif")}}');
                background-position: center;
                background-size: cover; */
                left: 295px;
            }
            .main-content {
                margin-left: 280px;
                background: #F5F5F5;
            }
            .page-content {
                padding: calc(75px + 1.5rem) calc(1.5rem / 2) 60px calc(1.5rem / 2);
            }

            .page-title-box {
                z-index: 2;
                position: relative;
                margin: -10px -1.5rem 5px -24px;
                box-shadow: none;
                background: #F5F5F5;
            }

            /* sidebar css */
            .simplebar-content-wrapper, .simplebar-wrapper, .simplebar-height-auto-observer-wrapper, .simplebar-height-auto-observer, .simplebar-mask {
                overflow: visible !important;
            }
            .navbar-menu {
                width: 280px;
            }
            #navbar-nav {
                padding-top: 15px;
            }
            .navbar-menu .navbar-nav .nav-link {
                font-family: "Merriweather", serif;
                font-optical-sizing: auto;
            }
            .navbar-nav .nav-link:hover {
                outline: 2px solid rgba(0, 255, 255, 0.8);
                box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
                transform: none !important;
                transition: all 0.3s ease-in-out;
                background: none;
                margin-left: 0;
            }
            .navbar-menu .navbar-nav .nav-link.active {
                outline: 2px solid rgba(0, 255, 255, 0.8);
                box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
                transform: none !important;
                background: none;
                margin-left: 0;
            }
            .navbar-menu .navbar-nav .nav-sm {
                margin-left: 0;
                padding-left: 0;
            }
            .navbar-menu .navbar-nav .nav-sm .nav-link {
                padding-left: 55px !important;
                font-size: 14px;
                font-family: "Merriweather", serif;
                font-optical-sizing: auto;
            }
            .navbar-menu .navbar-nav .nav-sm .nav-link::before {
                left: 30px;
            }

            .form-switch-custom .form-check-input::before {
                top: -6px;
            }
            
            /* card css */
            .card {
                -webkit-box-shadow: 0 0px 3px rgba(87, 87, 87, 0.24) !important;
                box-shadow: 0 0px 3px rgba(87, 87, 87, 0.24) !important;
            }

            .card-1 {
                background: linear-gradient(90deg, #2C003E, #1A0028, #0D0015);
                box-shadow: 0 5px 15px rgba(32, 58, 67, 0.5);
                transition: all 0.3s ease-in-out;
                position: relative;
                overflow: hidden;
            }
            .card-2 {
                background: linear-gradient(90deg, #001F3F, #003366, #004080);
                box-shadow: 0 5px 15px rgba(32, 58, 67, 0.5);
                transition: all 0.3s ease-in-out;
                position: relative;
                overflow: hidden;
            }
            .card-3 {
                background: linear-gradient(90deg, #1a1a2e, #16213e, #0f3460);
                box-shadow: 0 5px 15px rgba(32, 58, 67, 0.5);
                transition: all 0.3s ease-in-out;
                position: relative;
                overflow: hidden;
            }
            .card-4 {
                background: linear-gradient(90deg, #0F2027, #2C5364, #485563);
                box-shadow: 0 5px 15px rgba(32, 58, 67, 0.5);
                transition: all 0.3s ease-in-out;
                position: relative;
                overflow: hidden;
            }

            .avatar-title {
                background: transparent;
                border: 1px solid #fff;
            }

            .custom-card {
                background: linear-gradient(90deg, #0F2027, #203A43, #2C5364);
                border-radius: 5px;
                box-shadow: 0 5px 15px rgba(44, 83, 100, 0.5);
                transition: all 0.3s ease-in-out;
                height: 150px;
                display: flex;
                align-items: center;
                padding: 20px;
            }

            .custom-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(44, 83, 100, 0.8);
            }

            .custom-icon {
                width: 80px;
                height: 80px;
                display: flex;
                justify-content: center;
                align-items: center;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                font-size: 2.5rem;
                color: white;
                flex-shrink: 0;
            }
            .custom-text {
                text-align: right;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .custom-text .title {
                font-size: 1.2rem;
                font-weight: bold;
                text-transform: uppercase;
                color: white;
                margin-bottom: 5px;
            }

            .custom-text .count {
                font-size: 2.5rem;
                font-weight: bold;
                color: white;
                margin: 0;
            }

            .fullscreen {
                background: white;
            }
            .no-user-image-found i {
                font-size: 20px;
                color: #00000080;
                border: 1px solid white;
                padding: 5px;
                border-radius: 20px;
                background: white;
                box-shadow: 0 0 10px 2px #00000040;
            }
            
            #camera {
                width: 200px;
                height: 200px;
                /* border: 1px solid black; */
                margin-left: 25%;
            }
            
            li.first-dropdown:last-child {
                padding-bottom: 50px;
            }
            
            .profile-wid-bg {
                background: url('{{asset("frontend/img/background-image2.jpg")}}');
            }
            .profile-wid-bg::before {
                /* background: linear-gradient(45deg, #d48154, #6176d8) !important; */
                background: linear-gradient(90deg, #0F2027, #203A43, #2C5364) !important;
            }
            .logo-sm img {
                width: 100% !important;
                padding-right: 19px;
            }

            /* footer css */
            .footer {
                background: linear-gradient(90deg, #0F2027, #203A43, #2C5364) !important;
                margin: 10px 10px 10px 52px;
                border-radius: 9px;
                color: white !important;
                font-size: 14px;
            }
            .footer a {
                color: white !important;
                font-size: 14px;
            }

            /* selec2 css */
            .select2-container--default .select2-selection--single {
                border: 1px solid #d2d2d2 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 35px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 35px !important;
            }
            .select2-container .select2-selection--single {
                height: 38px !important;
            }

            /* button css */
            .btn-primary {
                color: #fff;
                background: linear-gradient(90deg, #0F2027, #203A43, #2C5364);
                border: none;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
                z-index: 1;
            }

            .btn-primary::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, #1A2F36, #2D4A53, #3B6476);
                transition: all 0.4s ease;
                z-index: -1;
            }

            .btn-primary:hover::after {
                left: 0;
            }

            .btn-warning {
                color: #fff;
                background: linear-gradient(90deg, #FFC107, #FFA000, #FF8F00);
                border: none;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
                z-index: 1;
            }

            .btn-warning::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, #FFCA28, #FFB300, #FFA21A);
                transition: all 0.4s ease;
                z-index: -1;
            }

            .btn-warning:hover::after {
                left: 0;
            }

            .btn-danger {
                color: #fff;
                background: linear-gradient(90deg, #DC3545, #C82333, #B21F2D);
                border: none;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
                z-index: 1;
            }

            .btn-danger::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, #E4606D, #D9534F, #C9302C);
                transition: all 0.4s ease;
                z-index: -1;
            }

            .btn-danger:hover::after {
                left: 0;
            }

            .btn-info {
                color: #fff;
                background: linear-gradient(90deg, #17A2B8, #138496, #117A8B);
                border: none;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
                z-index: 1;
            }

            .btn-info::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, #2BC4DB, #29B6F6, #26A69A);
                transition: all 0.4s ease;
                z-index: -1;
            }

            .btn-info:hover::after {
                left: 0;
            }

            .btn-success {
                color: #fff;
                background: linear-gradient(90deg, #28A745, #218838, #1E7E34);
                border: none;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
                z-index: 1;
            }

            .btn-success::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, #34CE57, #2ECC71, #27AE60);
                transition: all 0.4s ease;
                z-index: -1;
            }

            .btn-success:hover::after {
                left: 0;
            }

            /* loader css */
            .bbs-loader-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                z-index: 1000;
                background: #00000088;
                opacity: 0.9;
            }

            #loader-1 #loader{
                position: relative;
                left: 50%;
                top: 50%;
                height: 20vw;
                width: 20vw;
                margin: -10vw 0 0 -10vw; 
                border: 3px solid transparent;
                /* border-top-color: #3498db; */
                /* border-bottom-color: #3498db;  */
                border-radius: 50%;
                z-index: 2;
                -webkit-animation: spin 2s linear infinite;
                -moz-animation: spin 2s linear infinite;
                -o-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }
            #loader-1 #loader:before{
                content: "";
                position: absolute;
                top:2%;
                bottom: 2%;
                left: 2%;
                right: 2%; 
                border: 3px solid transparent;
                z-index: 2;
                /* border-top-color: #db213a; */
                border-radius: 50%;
                -webkit-animation: spin 3s linear infinite;
                -moz-animation: spin 3s linear infinite;
                -o-animation: spin 3s linear infinite;
                animation: spin 3s linear infinite;
            }
            #loader-1 #loader:after{
                content: "";
                position: absolute;
                top:5%;
                bottom: 5%;
                left: 5%;
                right: 5%; 
                border: 3px solid transparent;
                /* border-top-color: #dec52d; */
                z-index: 2;
                border-radius: 50%;
                -webkit-animation: spin 1.5s linear infinite;
                -moz-animation: spin 1.5s linear infinite;
                -o-animation: spin 1.5s linear infinite;
                animation: spin 1.5s linear infinite;
            }

            /* sidebar & header circle animation */
            .circles{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: -35;
            }
            .circles li{
                position: absolute;
                display: block;
                list-style: none;
                width: 20px;
                height: 20px;
                background: rgba(255, 255, 255, 0.2);
                animation: animate 25s linear infinite;
                bottom: -150px;
                
            }
            .circles li:nth-child(1){
                left: 15%;
                width: 80px;
                height: 80px;
                animation-delay: 0s;
            }
            .circles li:nth-child(2){
                left: 10%;
                width: 20px;
                height: 20px;
                animation-delay: 2s;
                animation-duration: 12s;
            }
            .circles li:nth-child(3){
                left: 70%;
                width: 20px;
                height: 20px;
                animation-delay: 4s;
            }
            .circles li:nth-child(4){
                left: 40%;
                width: 60px;
                height: 60px;
                animation-delay: 0s;
                animation-duration: 18s;
            }
            .circles li:nth-child(5){
                left: 65%;
                width: 20px;
                height: 20px;
                animation-delay: 0s;
            }
            .circles li:nth-child(6){
                left: 75%;
                width: 110px;
                height: 110px;
                animation-delay: 3s;
                border-radius: 100px;
            }
            .circles li:nth-child(7){
                left: 12%;
                width: 150px;
                height: 150px;
                animation-delay: 7s;
                border-radius: 100px;
            }
            .circles li:nth-child(8){
                left: 50%;
                width: 25px;
                height: 25px;
                animation-delay: 10s;
                border-radius: 30px;
            }
            .circles li:nth-child(9){
                left: 20%;
                width: 15px;
                height: 15px;
                animation-delay: 2s;
                animation-duration: 35s;
            }
            .circles li:nth-child(10){
                left: 85%;
                width: 150px;
                height: 150px;
                animation-delay: 0s;
                animation-duration: 11s;
            }
            .circles li:nth-child(11){
                left: 3%;
                width: 50px;
                height: 50px;
                animation-delay: 0s;
                animation-duration: 5s;
                border-radius: 30px;
            }
            .circles li:nth-child(12){
                left: 48%;
                width: 70px;
                height: 70px;
                animation-delay: 0s;
                animation-duration: 25s;
            }
            .circles li:nth-child(13){
                left: 95%;
                width: 30px;
                height: 30px;
                animation-delay: 0s;
                animation-duration: 5s;
                border-radius: 30px;
            }
            .circles li:nth-child(14){
                left: 22%;
                width: 150px;
                height: 150px;
                animation-delay: 6s;
                animation-duration: 17s;
            }
            .circles li:nth-child(15){
                left: 55%;
                width: 150px;
                height: 150px;
                animation-delay: 0s;
                animation-duration: 8s;
                border-radius: 100px;
            }
            .circles li:nth-child(16){
                left: 29%;
                width: 150px;
                height: 150px;
                animation-delay: 0s;
                animation-duration: 3s;
            }
            .circles li:nth-child(17){
                left: 39%;
                width: 150px;
                height: 150px;
                animation-delay: 3s;
                animation-duration: 11s;
                border-radius: 100px;
            }
            .circles li:nth-child(18){
                -webkit-animation-delay: 3s !important;
                animation-delay: 3s !important;
                top: 25px;
                left: 70vw;
                width: 10px;
                height: 10px;
                border: solid 1px #d3d9e678;
                transform-origin: top left;
                border-radius: 30px;
                transform: scale(0) rotate(0deg) translate(-50%, -50%);
                -webkit-animation: cube 12s ease-in forwards infinite;
                        animation: cube 12s ease-in forwards infinite;
            }
            .circles li:nth-child(19){
                -webkit-animation-delay: 5s !important;
                animation-delay: 5s !important;
                top: 30px;
                left: 25;
                width: 10px;
                height: 10px;
                border: solid 1px #d3d9e678;
                transform-origin: top left;
                transform: scale(0) rotate(0deg) translate(-50%, -50%);
                -webkit-animation: cube 12s ease-out forwards infinite;
                        animation: cube 12s ease-out forwards infinite;
            }
            .circles li:nth-child(20){
                -webkit-animation-delay: 8s !important;
                animation-delay: 8s !important;
                top: 85px;
                left: 45vw;
                width: 10px;
                height: 10px;
                border: solid 1px #d3d9e678;
                transform-origin: top left;
                transform: scale(0) rotate(0deg) translate(-50%, -50%);
                -webkit-animation: cube 12s ease-in forwards infinite;
                        animation: cube 12s ease-in forwards infinite;
            }
            @keyframes animate {

                0%{
                    transform: translateY(0) rotate(0deg);
                    opacity: 1;
                    /* border-radius: 0; */
                }

                100%{
                    transform: translateY(-1000px) rotate(720deg);
                    opacity: 0;
                    /* border-radius: 50%; */
                }

            }
            @-webkit-keyframes cube {
                from {
                    transform: scale(0) rotate(0deg) translate(-50%, -50%);
                    opacity: 1;
                }
                to {
                    transform: scale(20) rotate(960deg) translate(-50%, -50%);
                    opacity: 0;
                }
            }

            @keyframes cube {
                from {
                    transform: scale(0) rotate(0deg) translate(-50%, -50%);
                    opacity: 1;
                }
                to {
                    transform: scale(20) rotate(960deg) translate(-50%, -50%);
                    opacity: 0;
                }
            }

            /* responsive css */
            @media screen and (min-width: 1030px) {
                .app-menu {
                    border: 9px solid white !important;
                    border-radius: 20px !important;
                }
            }

            @media screen and (max-width: 467px) {
                
            }
        </style>

        @stack('css')
    </head>

    <body>
