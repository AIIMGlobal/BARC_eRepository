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

        @if (Auth::user()->user_type == 4)
            @can('total_saved_content')
                <div class="col-md-3 col-sm-12">
                    <a href="#" class="text-decoration-none">
                        <div class="custom-card">
                            <div class="custom-icon">
                                <i class="bx bx-file"></i>
                            </div>
               
                            <div class="custom-text">
                                <p class="title">Total Saved Contents</p>
                               
                                <h2 class="count">
                                    <span class="counter-value" data-target="0">0</span>
                                </h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
 
            @can('total_favourite_content')
                <div class="col-md-3 col-sm-12">
                    <a href="#" class="text-decoration-none">
                        <div class="custom-card">
                            <div class="custom-icon">
                                <i class="bx bx-file"></i>
                            </div>
               
                            <div class="custom-text">
                                <p class="title">Total Favourite Contents</p>
                               
                                <h2 class="count">
                                    <span class="counter-value" data-target="0">0</span>
                                </h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
        @endif
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