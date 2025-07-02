@push('css')
    <style>
        .barChart-container {
            width: 500px;
            height: 500px;
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
                    <div class="custom-card bg-primary">
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
                    <div class="custom-card bg-success">
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
                    <div class="custom-card bg-info">
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
                    <div class="custom-card bg-danger">
                        <div class="custom-icon">
                            <i class="bx bx-file"></i>
                        </div>
            
                        <div class="custom-text">
                            <p class="title">Repository Contents</p>
                            
                            <h2 class="count">
                                <span class="counter-value" data-target="{{ $contentCount ?? 0 }}">{{ $contentCount ?? 0 }}</span>
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
                        <div class="custom-card bg-primary">
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
                        <div class="custom-card bg-success">
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
                            <div class="custom-icon bg-info">
                                <i class="bx bx-file"></i>
                            </div>
               
                            <div class="custom-text">
                                <p class="title">Total Watch Later Contents</p>
                               
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
        @can('category_bar_chart')
            <div class="col-md-4">
                <h4 class="text-center">Category Record</h4>

                <div class="barChart-container">
                    <canvas id="barChart" width="500" height="500"></canvas>
                </div>
            </div>
        @endcan

        @can('content_line_chart')
            @if (in_array(Auth::user()->role_id, [1, 2, 3]))
                @push('css')
                    <style>
                        .pieChart-container {
                            max-width: 500px;
                            max-height: 500px;
                            width: 100%;
                            height: 100%;
                        }
                    </style>
                @endpush

                <div class="col-md-4">
                    <h4 class="text-center">Content Graph ({{ date('Y') }})</h4>

                    <div class="pieChart-container">
                        <canvas id="lineChart" width="500" height="500"></canvas>
                    </div>
                </div>
            @else
                @push('css')
                    <style>
                        .pieChart-container {
                            width: 100%;
                            height: 100%;
                        }
                    </style>
                @endpush

                <div class="col-md-12">
                    <h4 class="text-center">Content Graph</h4>

                    <div class="pieChart-container">
                        <canvas id="lineChart" height="500" style="width: 100%;"></canvas>
                    </div>
                </div>
            @endif
        @endcan

        @can('organization_pie_chart')
            <div class="col-md-4">
                <h4 class="text-center">Organization-wise Users</h4>

                <div class="pieChart-container">
                    <canvas id="pieChart" width="500" height="500"></canvas>
                </div>
            </div>
        @endcan
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

        function generateGradientColors(ctx) {
            var r = Math.floor(Math.random() * 256);
            var g = Math.floor(Math.random() * 256);
            var b = Math.floor(Math.random() * 256);

            var gradient = ctx.createLinearGradient(0, 0, 0, 200);

            gradient.addColorStop(0, `rgba(${r}, ${g}, ${b}, 0.6)`);
            gradient.addColorStop(1, `rgba(${Math.min(r + 50, 255)}, ${Math.min(g + 50, 255)}, ${Math.min(b + 50, 255)}, 0.2)`);

            var borderColor = `rgb(${Math.max(r - 20, 0)}, ${Math.max(g - 20, 0)}, ${Math.max(b - 20, 0)})`;

            return { gradient, borderColor };
        }

        var ctx = document.getElementById('lineChart').getContext('2d');

        var colors = generateGradientColors(ctx);

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.gradient,
                    borderColor: colors.borderColor,
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
                            display: false,
                            text: 'Month (Year)'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;

                                return `${label}: ${value} contents`;
                            }
                        }
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

        function generateGradientColors(ctx, count) {
            var gradients = [];
            var borderColors = [];

            for (var i = 0; i < count; i++) {
                var r = Math.floor(Math.random() * 256);
                var g = Math.floor(Math.random() * 256);
                var b = Math.floor(Math.random() * 256);

                var gradient = ctx.createLinearGradient(0, 0, 400, 400);

                gradient.addColorStop(0, `rgb(${r}, ${g}, ${b})`);
                gradient.addColorStop(1, `rgb(${Math.max(r - 50, 0)}, ${Math.max(g - 50, 0)}, ${Math.max(b - 50, 0)})`);

                gradients.push(gradient);

                borderColors.push(`rgb(${Math.max(r - 20, 0)}, ${Math.max(g - 20, 0)}, ${Math.max(b - 20, 0)})`);
            }

            return { gradients, borderColors };
        }

        var colors = generateGradientColors(ctx, labels.length);

        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Approved Users by Organization',
                    data: percentages,
                    backgroundColor: colors.gradients,
                    borderColor: colors.borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
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