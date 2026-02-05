@extends('admin.layouts.app')

@section('title', 'Raporlar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">
            <i class="fas fa-chart-bar mr-2"></i>
            Raporlar ve İstatistikler
        </h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.reports.detailed') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center">
                <i class="fas fa-chart-line mr-2 text-lg"></i>
                Detaylı Raporlar
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Toplam Üye</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalMembers) }}</p>
                    <p class="text-sm text-green-600">
                        {{ number_format($activeMembers) }} aktif, {{ number_format($inactiveMembers) }} pasif
                    </p>
                </div>
            </div>
        </div>

        <!-- This Month Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-euro-sign text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Bu Ay Gelir</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($thisMonthPayments, 2) }} €</p>
                    <p class="text-sm text-gray-600">
                        {{ now()->formatTr('F Y') }}
                    </p>
                </div>
            </div>
        </div>


        <!-- Gecikmiş Aidat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Gecikmiş Aidat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($overdueDues) }}</p>
                    <p class="text-sm text-red-600">
                        {{ number_format($overdueAmount, 2) }} € tutarında
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ number_format($membersWithOverdueDues) }} üyede gecikme
                    </p>
                </div>
            </div>
        </div>

        <!-- Bu Yıl Gelir -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-indigo-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ now()->year }} Yılı Gelir</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($thisYearPayments, 2) }} €</p>
                    <p class="text-sm text-indigo-600">
                        <i class="fas fa-chart-line mr-1"></i>
                        Bu yıl toplam
                    </p>
                </div>
            </div>
        </div>

        <!-- Önceki Yıl Gelir -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ now()->year - 1 }} Yılı Gelir</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($lastYearPayments, 2) }} €</p>
                    <p class="text-sm text-orange-600">
                        <i class="fas fa-history mr-1"></i>
                        Önceki yıl toplam
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                Aylık Gelir Trendi (Son 12 Ay)
            </h3>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Member Growth Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-user-plus mr-2 text-green-500"></i>
                Üye Artışı (Son 12 Ay)
            </h3>
            <div class="h-80">
                <canvas id="memberChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Age and Birth Place Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Age Groups Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-users mr-2 text-orange-500"></i>
                Yaş Grupları Dağılımı
            </h3>
            <div class="h-80">
                <canvas id="ageChart"></canvas>
            </div>
        </div>

        <!-- Birth Places Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>
                Memleket Dağılımı (Top 10)
            </h3>
            <div class="h-80">
                <canvas id="birthPlaceChart"></canvas>
            </div>
        </div>
    </div>



</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($monthlyRevenue)->pluck('month')) !!},
            datasets: [{
                label: 'Gelir (€)',
                data: {!! json_encode(collect($monthlyRevenue)->pluck('revenue')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
            }
        }
    });

    // Member Growth Chart
    const memberCtx = document.getElementById('memberChart').getContext('2d');
    const memberChart = new Chart(memberCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($memberGrowth)->pluck('month')) !!},
            datasets: [{
                label: 'Yeni Üyeler',
                data: {!! json_encode(collect($memberGrowth)->pluck('count')) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Age Groups Chart
    const ageCtx = document.getElementById('ageChart').getContext('2d');

    // Debug: Console'da yaş grupları verilerini kontrol et
    console.log('Age Groups Data:', {!! json_encode($ageGroups) !!});
    console.log('Age Groups Labels:', {!! json_encode(array_keys($ageGroups)) !!});
    console.log('Age Groups Values:', {!! json_encode(array_values($ageGroups)) !!});
    console.log('Debug Info:', {!! json_encode($debugInfo) !!});

    // İlk 5 üyenin doğum tarihi ve yaş bilgilerini kontrol et
    @php
        $sampleMembers = \App\Models\Member::whereNotNull('birth_date')->take(5)->get();
        $sampleData = [];
        foreach($sampleMembers as $member) {
            try {
                $birthDate = \Carbon\Carbon::parse($member->birth_date);
                $age = $birthDate->diffInYears(now());
                $sampleData[] = [
                    'id' => $member->id,
                    'birth_date' => $member->birth_date,
                    'calculated_age' => $age
                ];
            } catch (\Exception $e) {
                $sampleData[] = [
                    'id' => $member->id,
                    'birth_date' => $member->birth_date,
                    'error' => $e->getMessage()
                ];
            }
        }
    @endphp
    console.log('Sample Members Data:', {!! json_encode($sampleData) !!});

    // Veri kontrolü
    const ageGroupsData = {!! json_encode($ageGroups) !!};
    const hasData = Object.values(ageGroupsData).some(value => value > 0);

    console.log('Has data:', hasData);
    console.log('Age groups data:', ageGroupsData);

    const ageChart = new Chart(ageCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(ageGroupsData),
            datasets: [{
                data: Object.values(ageGroupsData),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                const dataset = data.datasets[0];
                                return data.labels.map((label, i) => {
                                    const value = dataset.data[i];
                                    return {
                                        text: `${label}: ${value} kişi`,
                                        fillStyle: dataset.backgroundColor[i],
                                        strokeStyle: dataset.borderColor[i],
                                        lineWidth: dataset.borderWidth,
                                        pointStyle: 'circle',
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} kişi (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Birth Places Chart
    const birthPlaceCtx = document.getElementById('birthPlaceChart').getContext('2d');
    const birthPlaceChart = new Chart(birthPlaceCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($birthPlaces->pluck('birth_place')) !!},
            datasets: [{
                label: 'Üye Sayısı',
                data: {!! json_encode($birthPlaces->pluck('count')) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
</script>
@endsection
