@extends('layouts.app')

@push('css')
    <link href="{{ asset('assets/css/dashboard-apexcharts.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Produits</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $totalProduits }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <a href="{{ route('produits.index') }}" class="text-nowrap">Voir tous les produits</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Catégories</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $totalCategories }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-th-large"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <a href="{{ route('categories.index') }}" class="text-nowrap">Gérer les catégories</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Mouvements</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $totalMouvements }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                            <i class="fas fa-exchange-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <a href="{{ route('mouvements.index') }}" class="text-nowrap">Voir l'historique</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Utilisateurs</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $totalUtilisateurs }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('user.index') }}" class="text-nowrap">Gérer les utilisateurs</a>
                                    @else
                                        <span class="text-nowrap">Utilisateurs enregistrés</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">Aperçu Financier</h6>
                                <h2 class="text-white mb-0">Valeur des Mouvements de Stock ({{ $selectedYear }})</h2>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="yearDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ni ni-calendar-grid-58"></i>
                                            Année {{ $selectedYear }}
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="yearDropdown">
                                            <h6 class="dropdown-header">Sélectionner l'année</h6>
                                            @foreach($availableYears as $year)
                                                <a class="dropdown-item {{ $year == $selectedYear ? 'active' : '' }}" 
                                                   href="{{ route('dashboard') }}?year={{ $year }}">
                                                    <i class="ni ni-calendar-grid-58 mr-2"></i>
                                                    {{ $year }}
                                                    @if($year == \Carbon\Carbon::now()->year)
                                                        <span class="badge badge-primary badge-sm ml-2">Actuelle</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart" style="position: relative; height: 350px;">
                            <div id="chart-sales" style="height: 100%;"></div>
                        </div>
                        <!-- Legend -->
                        <div class="chart-legend mt-3">
                            <div class="row">
                                <div class="col-6 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="legend-color" style="width: 15px; height: 15px; background-color: #5e72e4; margin-right: 8px; border-radius: 2px;"></div>
                                        <span class="text-sm text-muted">Entrées - Valeur (MAD)</span>
                                    </div>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="legend-color" style="width: 15px; height: 15px; background-color: #f5365c; margin-right: 8px; border-radius: 2px;"></div>
                                        <span class="text-sm text-muted">Sorties - Valeur (MAD)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">Total des commandes</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart-orders" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
    <script>
        // Stock Movements Chart (ApexCharts)
        document.addEventListener('DOMContentLoaded', function() {
            var chartData = @json($chartData);
            
            // Extract data for stock movements chart
            var labels = chartData.map(function(item) {
                return item.month;
            });
            
            var entréesData = chartData.map(function(item) {
                return item.entrées;
            });
            
            var sortiesData = chartData.map(function(item) {
                return item.sorties;
            });

            // Stock Movements Chart Configuration (ApexCharts)
            var stockOptions = {
                series: [
                    {
                        name: 'Valeur Entrées (MAD)',
                        data: entréesData,
                        color: '#5e72e4'
                    },
                    {
                        name: 'Valeur Sorties (MAD)',
                        data: sortiesData,
                        color: '#f5365c'
                    }
                ],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    background: 'transparent',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 300
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    lineCap: 'round'
                },
                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            colors: '#8898aa'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    min: 0,
                    labels: {
                        style: {
                            colors: '#8898aa'
                        },
                        formatter: function(value) {
                            return value.toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'MAD',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).replace('MAD', 'MAD');
                        }
                    }
                },
                grid: {
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    enabled: true,
                    shared: true,
                    intersect: false,
                    theme: 'dark',
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'MAD',
                                minimumFractionDigits: 2
                            }).replace('MAD', 'MAD');
                        }
                    }
                },
                markers: {
                    size: 4,
                    strokeWidth: 2,
                    strokeColors: '#fff',
                    hover: {
                        size: 6
                    }
                }
            };
            
            // Create Stock Movements Chart (ApexCharts)
            var stockChart = new ApexCharts(document.querySelector("#chart-sales"), stockOptions);
            stockChart.render();
            
            // Orders Chart (Chart.js - Original Implementation)
            var ordersCtx = document.getElementById('chart-orders').getContext('2d');
            var ordersChart = new Chart(ordersCtx, {
                type: 'bar',
                data: {
                    labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Sales',
                        data: [25, 20, 30, 22, 17, 29],
                        backgroundColor: '#5e72e4'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            
            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                if (stockChart) stockChart.destroy();
                if (ordersChart) ordersChart.destroy();
            });
            
            // Add year selection handler
            document.querySelectorAll('#yearDropdown + .dropdown-menu .dropdown-item').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    var year = this.href.split('year=')[1];
                    if (year && year !== '{{ $selectedYear }}') {
                        // Show loading state
                        var button = document.getElementById('yearDropdown');
                        var originalText = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';
                        button.disabled = true;
                        
                        // Navigate to new year
                        window.location.href = this.href;
                    }
                });
            });
        });
    </script>
@endpush