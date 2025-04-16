<header id="page-topbar">
    {{-- <div class="layout-width" style="background: linear-gradient(45deg, #c27e1e, #c69c60);"> --}}
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('admin.home') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('backend-assets/assets/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('backend-assets/assets/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="{{ route('admin.home') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('backend-assets/assets/images/logo-sm.png') }}" alt="" height="22">
                        </span>

                        <span class="logo-lg">
                            <img src="{{ asset('backend-assets/assets/images/logo-light.png') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span style="background: white;"></span>
                        <span style="background: white;"></span>
                        <span style="background: white;"></span>
                    </span>
                </button>

            </div>

            <div class="d-flex text-center">
                <h3 class="text-white mt-2" style="font-weight: 700; font-size: 30px; font-family: 'Merriweather', serif; font-optical-sizing: auto;">
                    {{ $global_setting->title ?? "" }}
                </h3>
            </div>

            <div class="d-flex align-items-center">
                {{-- @if(Auth::user() != NULL)
                    <div class="ms-1 header-item d-none d-sm-flex">
                        <a href="{{ route('admin.language_change') }}" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle text-white" title="Language">
                            @if (\App::getLocale('lang') == 'en')
                                BN
                            @else
                                EN
                            @endif
                        </a>
                    </div>
                @endif --}}

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen" title="Fullscreen">
                        <i class='bx bx-fullscreen fs-22' style="color: white;"></i>
                    </button>
                </div>

                {{-- <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode" title="Dark Mode">
                        <i class='bx bx-moon fs-22' style="color: white;"></i>
                    </button>
                </div> --}}

                @if(Auth::user() != NULL)
                    <x-notification/>
                @endif
                
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    @if(Auth::user() != NULL)
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                @if (Auth::user()->userInfo->image ?? '')
                                    <img class="rounded-circle header-profile-user" src="{{ asset('storage/userImages/' .  (Auth::user()->userInfo->image ?? '')) }}" alt="Header Avatar">
                                @else
                                    <div class="rounded-circle header-profile-user no-user-image-found">
                                        <i class="ri-shield-user-line"></i>
                                    </div>
                                @endif

                                <span class="text-start ms-xl-2">
                                    <span class="d-none d-xl-inline-block ms-1 fw-medium text-white user-name-text">{{ auth()->user()->first_name }}</span>
                                    <span class="d-none d-xl-block ms-1 fs-12 text-white user-name-sub-text">
                                        {{ auth()->user()->designation->name ?? '' }}
                                    </span>
                                </span>
                            </span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('admin.edit_profile') }}">
                                <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> 
                                <span class="align-middle">Profile</span>
                            </a>

                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> 
                                <span class="align-middle" data-key="t-logout">Log out</span>
                            </a>

                            <div class="d-none">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>

                        </div>
                    @endif
                </div>
            </div>

            <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div>
</header>