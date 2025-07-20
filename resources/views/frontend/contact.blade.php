<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>About BARC Repository | {{ $global_setting->title }}</title>
        <meta name="description" content="Discover the BARC Repository, a modern digital platform for accessing educational content like videos, PDF leaflets, and research papers.">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Favicon -->
        @if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}">
        @else
            <link rel="shortcut icon" type="image/x-icon" href="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg">
        @endif

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Google Web Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <!-- Custom CSS -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                color: #333;
            }
            .navbar {
                background: #228B22;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 5px 2rem;
            }
            .navbar-brand img {
                max-height: 80px;
                transition: transform 0.3s ease;
            }
            .navbar-brand img:hover {
                transform: scale(1.05);
            }
            .nav-link.btn {
                border-radius: 25px;
                padding: 0.5rem 1.5rem;
                font-weight: 500;
                transition: all 0.3s ease;
                width: 120px;
            }
            .nav-link.btn-primary {
                background: #3DB043;
                color: #fff;
                margin-right: 20px;
            }
            .nav-link.btn-primary:hover {
                background: #228B22;
            }
            .nav-link.btn-outline-primary {
                border: 2px solid #3DB043;
                color: #fff;
            }
            .nav-link.btn-outline-primary:hover {
                background: #3DB043;
                color: #fff;
            }
            .hero-section {
                padding: 3rem 0;
                background: #fff;
                border-radius: 15px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                margin: 0 auto;
                /* max-width: 1200px; */
                text-align: justify;
            }
            .hero-section h3{
                /* color: #3DB043; */
                padding-bottom: 10px;
            }
            .section-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #3DB043;
                margin-bottom: 0.5rem;
            }
            .section-subtitle {
                font-size: 1.2rem;
                color: #6c757d;
                margin-bottom: 0.5rem;
            }
            .feature-card {
                background: #228B22;
                border-radius: 10px;
                padding: 2rem;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                color: #fff;
                margin-bottom: 20px;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            }
            .feature-card i {
                font-size: 2rem;
                color: #fff;
                margin-bottom: 1rem;
            }
            .cta-button {
                border-radius: 25px;
                padding: 0.75rem 2rem;
                font-size: 1.1rem;
                font-weight: 500;
                background: #3DB043;
                color: #fff;
                border: none;
                transition: background 0.3s ease;
            }
            .cta-button:hover {
                background: #228B22;
            }
            footer {
                background: #228B22;
                color: #fff;
                padding: 0.5rem 0;
                text-align: center;
            }
            .contact-info {
                /* background: #f8f9fa; */
                border-radius: 10px;
                padding: 1rem 2rem;
                height: 100%;
                background: #228B22;
                color: #fff;
            }
            .contact-info i {
                font-size: 1.5rem;
                color: #fff;
                margin-right: 1rem;
            }
            .map-container {
                border-radius: 10px;
                overflow: hidden;
                height: 100%;
                min-height: 500px;
                margin-top: 20px;
            }
            .map-container iframe {
                width: 100%;
                height: 100%;
                border: none;
            }

            @media (max-width: 768px) {
                .hero-section {
                    padding: 3rem 1rem;
                }
                .section-title {
                    font-size: 2rem;
                }
                .nav-link.btn-primary {
                    margin-right: 0;
                    margin-top: 10px;
                    
                }
                .nav-link.btn {
                    margin-bottom: 15px;
                }
                .contact-section {
                    padding: 3rem 1rem;
                }
                .map-container {
                    min-height: 200px;
                }
            }
        </style>
    </head>
    
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                {{-- <div class="row">
                    <div class="col-md-12"> --}}
                        <a class="navbar-brand" href="{{ route('admin.home') }}">
                            @if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
                                <img src="{{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}" alt="Logo">
                            @else
                                <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Logo">
                            @endif
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary" href="{{ route('login') }}">Login</a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link btn btn-outline-primary" href="{{ route('register') }}">Register</a>
                                </li>
                            </ul>
                        </div>
                    {{-- </div>
                </div> --}}
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h1 class="section-title">Contact Us</h1>
                            <p class="section-subtitle">Reach out to us for any inquiries or support regarding the BARC Repository.</p>
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-12 mb-4 mb-lg-0">
                                <div class="contact-info">
                                    <h3>Contact Information</h3>

                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-envelope"></i>
                                        <div>
                                            <strong>Email:</strong> {{ $global_setting->email }}
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-phone"></i>
                                        <div>
                                            <strong>Phone:</strong> {{ $global_setting->mobile }}
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <strong>Address:</strong> {{ $global_setting->address }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="map-container">
                                    {!! $global_setting->social_link !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mb-0">&copy; {{ $global_setting->title }} {{ date('Y') }}. All Rights Reserved.</p>
                        <p class="mb-0">{!! $global_setting->copyright !!}</p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>