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

        @can('total_categories')
            <div class="col-md-3 col-sm-12">
                <a href="{{ route('admin.category.index') }}" class="text-decoration-none">
                    <div class="custom-card">
                        <div class="custom-icon">
                            <i class="bx bx-briefcase"></i>
                        </div>
            
                        <div class="custom-text">
                            <p class="title">Total Categories</p>
                            
                            <h2 class="count">
                                <span class="counter-value" data-target="{{ $categorys->count() ?? 0 }}">{{ $categorys->count() ?? 0 }}</span>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('document_count')
            <div class="col-md-3 col-sm-12">
                <a href="{{ route('admin.content.index') }}" class="text-decoration-none">
                    <div class="custom-card">
                        <div class="custom-icon">
                            <i class="bx bx-file"></i>
                        </div>
            
                        <div class="custom-text">
                            <p class="title">Repository Contents</p>
                            
                            <h2 class="count">
                                <span class="counter-value" data-target="{{ $contents->count() ?? 0 }}">{{ $contents->count() ?? 0 }}</span>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @if (Auth::user()->user_type == 4)
            @can('total_uploaded_contents')
                <div class="col-md-3 col-sm-12">
                    <a href="{{ route('admin.content.indexMyContent') }}" class="text-decoration-none">
                        <div class="custom-card">
                            <div class="custom-icon">
                                <i class="bx bx-file"></i>
                            </div>
               
                            <div class="custom-text">
                                <p class="title">Total Uploaded Contents</p>
                               
                                <h2 class="count">
                                    <span class="counter-value" data-target="{{ $uploadedCount ?? 0 }}">{{ $uploadedCount ?? 0 }}</span>
                                </h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan

            @can('total_favourite_content')
                <div class="col-md-3 col-sm-12">
                    <a href="{{ route('admin.content.indexFavorite') }}" class="text-decoration-none">
                        <div class="custom-card">
                            <div class="custom-icon">
                                <i class="bx bx-file"></i>
                            </div>
               
                            <div class="custom-text">
                                <p class="title">Total Favourite Contents</p>
                               
                                <h2 class="count">
                                    <span class="counter-value" data-target="{{ $favCount ?? 0 }}">{{ $favCount ?? 0 }}</span>
                                </h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan

            @can('total_saved_content')
                <div class="col-md-3 col-sm-12">
                    <a href="{{ route('admin.content.indexSaved') }}" class="text-decoration-none">
                        <div class="custom-card">
                            <div class="custom-icon">
                                <i class="bx bx-file"></i>
                            </div>
               
                            <div class="custom-text">
                                <p class="title">Total Saved Contents</p>
                               
                                <h2 class="count">
                                    <span class="counter-value" data-target="{{ $savedCount ?? 0 }}">{{ $savedCount ?? 0 }}</span>
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
            <h4 class="text-center">Content Graph ({{ date('Y') }})</h4>

            <div class="pieChart-container">
                {{-- <canvas id="pieChart" width="500" height="500"></canvas> --}}
                <canvas id="lineChart" width="500" height="500"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <h4 class="text-center">User Status</h4>

            <div class="pieChart-container">
                <canvas id="pieChart" width="500" height="500"></canvas>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        var categorys = @json($categorys);

        var labels = categorys.map(function(category) {
            return category.category_name;
        });
        var data = categorys.map(function(category) {
            return category.contents_count || 0;
        });

        function generateColors(count) {
            var backgroundColors = [];
            var borderColors = [];

            for (var i = 0; i < count; i++) {
                var r = Math.floor(Math.random() * 256);
                var g = Math.floor(Math.random() * 256);
                var b = Math.floor(Math.random() * 256);

                backgroundColors.push(`rgb(${r}, ${g}, ${b})`);
                borderColors.push(`rgb(${Math.max(r - 20, 0)}, ${Math.max(g - 20, 0)}, ${Math.max(b - 20, 0)})`);
            }

            return { backgroundColors, borderColors };
        }

        var colors = generateColors(labels.length);

        var ctx = document.getElementById('barChart').getContext('2d');

        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.backgroundColors,
                    borderColor: colors.borderColors,
                    borderWidth: 1,
                    barThickness: 20
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Content Uploaded'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Category'
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
        var contents = @json($contents);

        var labels = Object.keys(contents);
        var data = Object.values(contents);

        if (!labels.length) {
            labels = ['No Data'];
            data = [0];
        }

        function generateGradientColors(ctx, count) {
            var gradients = [];
            var borderColors = [];

            for (var i = 0; i < count; i++) {
                var r = Math.floor(Math.random() * 256);
                var g = Math.floor(Math.random() * 256);
                var b = Math.floor(Math.random() * 256);

                var gradient = ctx.createLinearGradient(0, 0, 0, 200);

                gradient.addColorStop(0, `rgba(${r}, ${g}, ${b}, 0.6)`);
                gradient.addColorStop(1, `rgba(${Math.min(r + 50, 255)}, ${Math.min(g + 50, 255)}, ${Math.min(b + 50, 255)}, 0.2)`);

                gradients.push(gradient);

                borderColors.push(`rgb(${Math.max(r - 20, 0)}, ${Math.max(g - 20, 0)}, ${Math.max(b - 20, 0)})`);
            }
            return { gradients, borderColors };
        }

        var ctx = document.getElementById('lineChart').getContext('2d');

        var colors = generateGradientColors(ctx, labels.length);

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.gradients[0],
                    borderColor: colors.borderColors[0],
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Content Uploaded'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
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
        var chartData = @json($chartData);

        var labels = chartData.labels;
        var counts = chartData.counts;
        var percentages = chartData.percentages;

        if (!labels.length) {
            labels = ['No Data'];
            counts = [0];
            percentages = [0];
        }

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

        var gradientGray = ctx.createLinearGradient(0, 0, 400, 400);
        gradientGray.addColorStop(0, '#616161');
        gradientGray.addColorStop(1, '#212121');

        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'User Status Distribution',
                    data: percentages,
                    backgroundColor: [
                        gradientBlue,
                        gradientGreen,
                        gradientRed,
                        gradientGray
                    ],
                    borderColor: [
                        'rgba(9, 0, 136, 1)',  
                        'rgba(24, 124, 25, 1)', 
                        'rgba(255, 0, 0, 1)', 
                        'rgba(33, 33, 33, 1)' 
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let percentage = context.raw || 0;
                                let count = counts[context.dataIndex] || 0;
                                
                                return `${label}: ${percentage}% (${count} users)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush