@push('css')
    <style>
        .barChart-container {
            width: 500px;
            height: 500px;
        }
        .pieChart-container {
            max-width: 500px;
            max-height: 500px;
            width: 100%;
            height: 100%;
        }
    </style>
@endpush

<div class="col-md-12">
    <h3>Welcome to {{ $global_setting->title }} Dashboard</h3>

    <div class="row mt-4">
        @can('user_count')
            <div class="col-md-3 col-sm-12">
                <a href="{{ route('admin.user.index') }}" class="text-decoration-none">
                    <div class="custom-card">
                        <div class="custom-icon">
                            <i class="bx bx-user"></i>
                        </div>
            
                        <div class="custom-text">
                            <p class="title">Registererd Users</p>

                            <h2 class="count">
                                <span class="counter-value" data-target="{{ $usersCount ?? 0 }}">{{ $usersCount ?? 0 }}</span>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('employee_count')
            <div class="col-md-3 col-sm-12">
                <a href="{{ route('admin.user.index') }}" class="text-decoration-none">
                    <div class="custom-card">
                        <div class="custom-icon">
                            <i class="bx bx-id-card"></i>
                        </div>
            
                        <div class="custom-text">
                            <p class="title">Total Employees</p>

                            <h2 class="count">
                                <span class="counter-value" data-target="{{ $employeesCount ?? 0 }}">{{ $employeesCount ?? 0 }}</span>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('project_count')
            <div class="col-md-3 col-sm-12">
                <a href="{{ route('admin.project.index') }}" class="text-decoration-none">
                    <div class="custom-card">
                        <div class="custom-icon">
                            <i class="bx bx-briefcase"></i>
                        </div>
            
                        <div class="custom-text">
                            <p class="title">Total Projects</p>
                            
                            <h2 class="count">
                                <span class="counter-value" data-target="{{ $employeesCount ?? 0 }}">{{ $employeesCount ?? 0 }}</span>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('document_count')
            <div class="col-md-3 col-sm-12">
                <a href="{{ route('admin.document.index') }}" class="text-decoration-none">
                    <div class="custom-card">
                        <div class="custom-icon">
                            <i class="bx bx-file"></i>
                        </div>
            
                        <div class="custom-text">
                            <p class="title">Total Contents</p>
                            
                            <h2 class="count">
                                <span class="counter-value" data-target="{{ $employeesCount ?? 0 }}">{{ $employeesCount ?? 0 }}</span>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        @endcan
    </div>

    <div class="row my-4">
        <div class="col-md-4">
            <h4 class="text-center">Category Record</h4>

            <div class="barChart-container">
                <canvas id="barChart" width="500" height="500"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <h4 class="text-center">Content Graph</h4>

            <div class="pieChart-container">
                {{-- <canvas id="pieChart" width="500" height="500"></canvas> --}}
                <canvas id="lineChart" width="500" height="500"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <h4 class="text-center">User Status</h4>

            <div class="pieChart-container">
                <canvas id="pieChartCategory" width="500" height="500"></canvas>
            </div>
        </div>
    </div>

    {{-- <div class="row mt-4">
        @can('project_summary_graph')
            <div class="col-md-12">
                <div class="card card-height-100" style="background: linear-gradient(45deg,#346c341c,#0000ff17);">
                    <div class="card-header border-0 align-items-center">
                        <form>
                            <div class="row g-3">
                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <h4 class="card-title mb-0">Project Summary</h4>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['from_date']) and $_GET['from_date']!='') value="{{$_GET['from_date']}}" @endif type="date" class="form-control search" name="from_date" placeholder="From Date">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['to_date']) and $_GET['to_date']!='') value="{{$_GET['to_date']}}" @endif type="date" class="form-control search" name="to_date" placeholder="To Date">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-1 col-sm-4">
                                    <div>
                                        <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> 
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>Filter
                                        </button>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-4">
                                    <div>
                                        <a style="max-width: 150px;" href="{{ route('admin.home') }}" class="btn btn-danger w-100"> 
                                            <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body p-0 pb-2">
                        <div>
                            <div id="chartColumn" class="apex-charts" dir="ltr" style="background: #f3f6f9;"></div>
                        </div>
                    </div>
                </div><!-- end card -->
            </div><!-- end col -->
        @endcan

        @can('active_dashboard_project_list')
            <div class="col-md-12">
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title flex-grow-1 mb-0">Active Projects</h4>

                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" class="btn btn-soft-info btn-sm">Export Report</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-nowrap table-centered align-middle">
                                <thead class="bg-light text-muted">
                                    <tr>
                                        <th scope="col">Project Name</th>
                                        <th scope="col">Project Lead</th>
                                        <th scope="col">Client Name</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Status</th>
                                    </tr><!-- end tr -->
                                </thead><!-- thead -->

                                <tbody>
                                    @if (count($latestProjects) > 0)
                                        @foreach ($latestProjects as $latestProject)
                                            <tr>
                                                <td class="fw-medium">{{ $latestProject->name }}</td>
                                                <td class="fw-medium">{{ $latestProject->pm->name_en ?? '' }}</td>
                                                <td class="fw-medium">{{ $latestProject->client->name_en ?? '' }}</td>
                                                <td class="text-muted">{{ date('d M, Y', strtotime($latestProject->end_date)) }}</td>
                                                
                                                <td>
                                                    @if ($latestProject->status == 3)
                                                        <span class="badge badge-soft-danger">Completed</span>
                                                    @elseif ($latestProject->status == 1)
                                                        <span class="badge badge-soft-primary">On Going</span>
                                                    @elseif ($latestProject->status == 2)
                                                        <span class="badge badge-soft-danger">Canceled</span>
                                                    @else
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    @endif
                                                </td>
                                                
                                            </tr><!-- end tr -->
                                        @endforeach

                                        <tr>
                                            <td colspan="5" style="text-align: center;"><a href="{{ route('admin.project.index') }}" class="btn btn-primary">View All Projects</a></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5">No record found!</td>
                                        </tr>
                                    @endif
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div>
                    </div>
                </div><!-- end card -->
            </div><!-- end col -->
        @endcan
    </div> --}}
</div>

@push('script')
    <script>
        var options = {
                series: [{
                data: [@foreach($projects as $project) {{ $project->amount }},  @endforeach]
            }],
            chart: {
                height: 350,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {
                    // console.log(chart, w, e)
                    }
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '10%',
                    distributed: false,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: [
                    @foreach($projects as $project) 
                        ['{!! Str::limit($project->name, 20, " ...") !!}'],  
                    @endforeach
                ],
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            }
        }

        var chart = new ApexCharts(document.querySelector("#chartColumn"), options);

        chart.render();
    </script>

    <script>
        var ctx = document.getElementById('barChart').getContext('2d');

        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Invention', 'Paper', 'News', 'Posts'],
                datasets: [{
                    data: [12, 19, 18, 15],
                    backgroundColor: [
                        '#3ACB3B',
                        '#FF0000',
                        '#0D47A1',
                        '#00A3AA'
                    ],
                    borderColor: [
                        'rgba(24, 124, 25, 1)',
                        'rgba(255, 0, 0, 1)',
                        'rgba(9, 0, 136, 1)',
                        'rgba(15, 32, 39, 1)'
                    ],
                    borderWidth: 1,
                    barThickness: 40
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: false,
                            text: 'Category'
                        }
                    },
                    x: {
                        title: {
                            display: false,
                            text: 'Months'
                        },
                        label: {
                            display: false
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

    <script>
        var ctx = document.getElementById('pieChartCategory').getContext('2d');

        var gradientGreen = ctx.createLinearGradient(0, 0, 400, 400);
        gradientGreen.addColorStop(0, '#3ACB3B');
        gradientGreen.addColorStop(1, '#0F4010');

        var gradientRed = ctx.createLinearGradient(0, 0, 100, 500);
        gradientRed.addColorStop(0, '#FF0000');
        gradientRed.addColorStop(1, '#800000');

        var gradientBlue = ctx.createLinearGradient(0, 0, 400, 500);
        gradientBlue.addColorStop(0, '#0B48A1');
        gradientBlue.addColorStop(1, '#0D47A1');

        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Inactive', 'Pending'],
                datasets: [{
                    label: 'Content Upload Stats',
                    data: [65, 25, 10],
                    backgroundColor: [
                        gradientGreen,
                        gradientRed,
                        gradientBlue,
                    ],
                    borderColor: [
                        'rgba(24, 124, 25, 1)',
                        'rgba(255, 0, 0, 1)',
                        'rgba(9, 0, 136, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;

                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });
    </script>

    {{-- <script>
        var ctx = document.getElementById('pieChart').getContext('2d');

        var gradientGreen = ctx.createLinearGradient(0, 0, 400, 400);
        gradientGreen.addColorStop(0, '#3ACB3B');
        gradientGreen.addColorStop(1, '#0F4010');

        var gradientRed = ctx.createLinearGradient(0, 0, 100, 500);
        gradientRed.addColorStop(0, '#FF0000');
        gradientRed.addColorStop(1, '#800000');

        var gradientBlue = ctx.createLinearGradient(0, 0, 400, 500);
        gradientBlue.addColorStop(0, '#0B48A1');
        gradientBlue.addColorStop(1, '#0D47A1');

        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Inactive', 'Pending'],
                datasets: [{
                    // label: 'User Status',
                    data: [15, 35, 20],
                    backgroundColor: [
                        gradientGreen,
                        gradientRed,
                        gradientBlue,
                    ],
                    borderColor: [
                        'rgba(24, 124, 25, 1)',
                        'rgba(255, 0, 0, 1)',
                        'rgba(9, 0, 136, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;

                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });
    </script> --}}

    <script>
        var ctx = document.getElementById('lineChart').getContext('2d');

        var gradientGreen = ctx.createLinearGradient(0, 0, 0, 200);
        gradientGreen.addColorStop(0, 'rgba(15, 64, 16, 0.6)');
        gradientGreen.addColorStop(1, 'rgba(58, 203, 59, 0.2)');

        var gradientRed = ctx.createLinearGradient(0, 0, 0, 200);
        gradientRed.addColorStop(0, 'rgba(128, 0, 0, 0.6)');
        gradientRed.addColorStop(1, 'rgba(255, 102, 102, 0.2)');

        var gradientBlue = ctx.createLinearGradient(0, 0, 0, 200);
        gradientBlue.addColorStop(0, 'rgba(5, 0, 68, 0.6)');
        gradientBlue.addColorStop(1, 'rgba(51, 51, 255, 0.2)');

        var gradientDark = ctx.createLinearGradient(0, 0, 0, 200);
        gradientDark.addColorStop(0, 'rgba(10, 23, 28, 0.6)');
        gradientDark.addColorStop(1, 'rgba(44, 83, 100, 0.2)');

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April'],
                datasets: [
                    {
                        data: [50, 75, 60, 90],
                        backgroundColor: gradientGreen,
                        borderColor: 'rgba(24, 124, 25, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        data: [30, 45, 55, 70],
                        backgroundColor: gradientRed,
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        data: [20, 35, 25, 50],
                        backgroundColor: gradientBlue,
                        borderColor: 'rgba(9, 0, 136, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        data: [10, 25, 15, 40],
                        backgroundColor: gradientDark,
                        borderColor: 'rgba(15, 32, 39, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Contents'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush