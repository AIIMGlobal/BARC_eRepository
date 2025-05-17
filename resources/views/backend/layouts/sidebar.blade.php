<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('admin.home') }}" class="logo logo-dark mt-2">
            <span class="logo-sm">
                <img src="@if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
                    {{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}
                @else
                    {{ 'https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg' }}
                @endif" alt="" height="50">
            </span>

            <span class="logo-lg">
                <img src="@if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
                    {{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}
                @else
                    {{ 'https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg' }}
                @endif" alt="" width="100">
            </span>
        </a>

        <!-- Light Logo-->
        <a href="{{ route('admin.home') }}" class="logo logo-light mt-2">
            <span class="logo-sm">
                <img src="@if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
                    {{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}
                @else
                    {{ 'https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg' }}
                @endif" alt="" height="50">
            </span>

            <span class="logo-lg">
                <img src="@if($global_setting->soft_logo && Storage::exists('public/soft_logo/' . $global_setting->soft_logo))
                    {{ asset('storage/soft_logo/' . $global_setting->soft_logo) }}
                @else
                    {{ 'https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg' }}
                @endif" alt="" width="100">
            </span>
        </a>

        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>

            <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                {{-- <li></li>
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
                <li></li> --}}
            </ul>

            <ul class="navbar-nav mt-2" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.home') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                
                @can('user_management')
                    <li class="nav-item first-dropdown">
                        <a class="nav-link menu-link" href="#rolePermission" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="rolePermission">
                            <i class="ri-shield-keyhole-line"></i> <span data-key="t-rolePermission">User Management</span>
                        </a>

                        <div class="collapse menu-dropdown" id="rolePermission">
                            <ul class="nav nav-sm flex-column">
                                @can('user_list')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user.index') }}" class="nav-link">User List</a>
                                    </li>
                                @endcan

                                {{-- @can('employee_list')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user.index') }}" class="nav-link">Employee List</a>
                                    </li>
                                @endcan --}}

                                {{-- @can('user_appraisal')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.appraisal.index') }}" class="nav-link">{{__('menu.Employee Appraisal')}}</a>
                                    </li>
                                @endcan --}}
                                
                                @can('user_category_management')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user_category.index') }}" class="nav-link">User Service Type</a>
                                    </li>
                                @endcan

                                {{-- @can('employee_category_report')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.report.employeeCategory') }}" class="nav-link" data-key="t-basic">User Category Summary</a>
                                    </li>
                                @endcan --}}

                                @can('all_roles')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.role.index') }}" class="nav-link">{{__('menu.Role List')}}</a>
                                    </li>
                                @endcan

                                @can('all_permissions')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.permission.index') }}" class="nav-link">{{__('menu.Permission List')}}</a>
                                    </li>
                                @endcan

                                @can('assign_permission_list')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.rolePermission.index') }}" class="nav-link">{{__('menu.Give Permission')}}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('manage_category')
                    <li class="nav-item first-dropdown">
                        <a class="nav-link menu-link" href="#category" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="category">
                            <i class="ri-building-line"></i> <span data-key="t-category">Category Management</span>
                        </a>

                        <div class="collapse menu-dropdown" id="category">
                            <ul class="nav nav-sm flex-column">
                                @can('category_list')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.category.index') }}" class="nav-link" data-key="t-crm">Category List</a>
                                    </li>
                                @endcan

                                @can('create_category')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.category.create') }}" class="nav-link" data-key="t-crm">Create Category</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('manage_content')
                    <li class="nav-item first-dropdown">
                        <a class="nav-link menu-link" href="#content" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="content">
                            <i class="ri-building-line"></i> <span data-key="t-content">Content Management</span>
                        </a>

                        <div class="collapse menu-dropdown" id="content">
                            <ul class="nav nav-sm flex-column">
                                @can('content_list')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.content.index') }}" class="nav-link" data-key="t-crm">Contents</a>
                                    </li>
                                @endcan

                                @can('content_list')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.content.indexMyContent') }}" class="nav-link" data-key="t-crm">My Contents</a>
                                    </li>
                                @endcan

                                @can('total_favourite_content')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.content.indexFavorite') }}" class="nav-link" data-key="t-crm">Favorite Contents</a>
                                    </li>
                                @endcan

                                @can('total_saved_content')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.content.indexSaved') }}" class="nav-link" data-key="t-crm">Saved Contents</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                
                @can('office_management')
                    <li class="nav-item first-dropdown">
                        <a class="nav-link menu-link" href="#office" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="office">
                            <i class="ri-building-line"></i> <span data-key="t-office">Organization Management</span>
                        </a>

                        <div class="collapse menu-dropdown" id="office">
                            <ul class="nav nav-sm flex-column">
                                @can('manage_office')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.office.index') }}" class="nav-link" data-key="t-crm">Manage Organizations</a>
                                    </li>
                                @endcan

                                @can('all_departments')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.department.index') }}" class="nav-link" data-key="t-crm">Manage Departments</a>
                                    </li>
                                @endcan

                                @can('all_designations')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.designation.index') }}" class="nav-link" data-key="t-crm">Manage Designations</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('manage_location')
                    <li class="nav-item first-dropdown">
                        <a class="nav-link menu-link" href="#location_management" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="location_management">
                            <i class="ri-map-pin-line"></i> <span data-key="t-location_management">Location Management</span>
                        </a>

                        <div class="collapse menu-dropdown" id="location_management">
                            <ul class="nav nav-sm flex-column">
                                @can('manage_region')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.region.index') }}" class="nav-link" data-key="t-crm">{{__('menu.Division List')}}</a>
                                    </li>
                                @endcan

                                @can('manage_district')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.district.index') }}" class="nav-link" data-key="t-crm">{{__('menu.District List')}}</a>
                                    </li>
                                @endcan

                                @can('manage_upazila')
                                    <li class="nav-item">
                                        <a href="{{route('admin.upazila.index')}}" class="nav-link" data-key="t-crm">{{__('menu.Upazila List')}}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('manage_report')
                    <li class="nav-item first-dropdown">
                        <a class="nav-link menu-link" href="#manage_report" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="manage_report">
                            <i class="ri-folder-chart-line"></i> <span data-key="t-manage_report">Report Management</span>
                        </a>

                        <div class="collapse menu-dropdown" id="manage_report">
                            <ul class="nav nav-sm flex-column">
                                @can('project_report')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.report.projectReport') }}" class="nav-link" data-key="t-basic">Project Summary Report</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('website_setting')
                    <li class="nav-item first-dropdown">
                        <a class="nav-link menu-link" href="#website_setting" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="website_setting">
                            <i class="ri-home-line"></i> <span data-key="t-website_setting">Settings</span>
                        </a>

                        <div class="collapse menu-dropdown" id="website_setting">
                            <ul class="nav nav-sm flex-column">
                                @can('setting_management')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="{{ route('admin.setting.index') }}">
                                            <span data-key="t-dashboards">Website Settings</span>
                                        </a>
                                    </li>
                                @endcan

                                {{-- @can('academic_form')
                                    <li class="nav-item">
                                        <a href="#academic_form" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="academic_form" data-key="t-signin">{{__('menu.Academic Form')}}</a>

                                        <div class="collapse menu-dropdown" id="academic_form">
                                            <ul class="nav nav-sm flex-column">

                                                @can('manage_exam')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.exam.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Exam')}}</a>
                                                    </li>
                                                @endcan

                                                @can('manage_exam_category')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.examCategory.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Exam Category')}}</a>
                                                    </li>
                                                @endcan

                                                @can('manage_subject')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.subject.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Subject')}}</a>
                                                    </li>
                                                @endcan

                                                @can('manage_subject_category')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.subjectCategory.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Subject Category')}}</a>
                                                    </li>
                                                @endcan

                                                @can('manage_institute')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.institute.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Institute')}}</a>
                                                    </li>
                                                @endcan
                                                
                                                @can('manage_board')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.board.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Board')}}</a>
                                                    </li>
                                                @endcan

                                                @can('manage_duration')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.duration.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Duration')}}</a>
                                                    </li>
                                                @endcan
                                                
                                                @can('manage_educational_form')
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.education_form.index') }}" class="nav-link" data-key="t-basic">{{__('menu.Manage Educational Form')}}</a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcan --}}
                            </ul>
                        </div>
                    </li>
                @endcan
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>