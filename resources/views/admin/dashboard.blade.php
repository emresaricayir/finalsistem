@extends('admin.layouts.app')

@section('title', 'Anasayfa')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-blue-600 to-purple-600 rounded-full"></div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Hoş Geldiniz, {{ Auth::user()->name }}</h1>
                </div>
                <p class="text-gray-600 text-base md:text-lg font-medium">Cami üyelik sisteminizi yönetin ve takip edin</p>
                <div class="flex items-center space-x-4 mt-4">
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>Sistem Aktif</span>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-calendar-alt text-xs"></i>
                        <span>{{ now()->setTimezone('Europe/Berlin')->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex md:items-center md:space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-500 font-medium">Son Giriş</p>
                    <p class="text-lg font-semibold text-gray-900">
                        @if(Auth::user()->last_login_at)
                            {{ Auth::user()->last_login_at->setTimezone('Europe/Berlin')->format('d.m.Y H:i') }}
                        @else
                            İlk giriş
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Total Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-hover">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium mb-1">Toplam Üye</p>
                <p class="text-2xl font-bold text-gray-900 mb-1">{{ $total_members }}</p>
                <p class="text-green-600 text-xs">
                    <i class="fas fa-arrow-up mr-1"></i>
                    {{ $active_members }} aktif
                </p>
            </div>
        </div>

        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant']))
        <!-- Pending Dues -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-hover">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium mb-1">Yıllık Beklenen Aidat</p>
                <p class="text-2xl font-bold text-orange-600 mb-1">{{ number_format($yearly_expected_dues, 2) }} €</p>
                <p class="text-orange-600 text-xs">
                    <i class="fas fa-calendar mr-1"></i>
                    Tüm üyeler
                </p>
            </div>
        </div>

        <!-- Overdue Dues -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-hover">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium mb-1">Gecikmiş Aidat</p>
                <p class="text-2xl font-bold text-red-600 mb-1">{{ $total_overdue_count }}</p>
                <p class="text-red-600 text-xs">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ number_format($total_overdue_amount, 2) }} €
                </p>
            </div>
        </div>

        <!-- This Month Income -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-hover">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium mb-1">Bu Ay Gelir</p>
                <p class="text-2xl font-bold text-green-600 mb-1">{{ number_format($this_month_dues_income, 2) }} €</p>
                <p class="text-green-600 text-xs">
                    <i class="fas fa-chart-line mr-1"></i>
                    Bu ay toplam
                </p>
            </div>
        </div>

        <!-- This Year Income -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-hover">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-white text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium mb-1">Bu Yıl Gelir</p>
                <p class="text-2xl font-bold text-blue-600 mb-1">{{ number_format($this_year_dues_income, 2) }} €</p>
                <p class="text-blue-600 text-xs">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ now()->year }} toplam
                </p>
            </div>
        </div>

        <!-- Last Year Income -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-hover">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar text-white text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium mb-1">Önceki Yıl</p>
                <p class="text-2xl font-bold text-purple-600 mb-1">{{ number_format($last_year_dues_income, 2) }} €</p>
                <p class="text-purple-600 text-xs">
                    <i class="fas fa-calendar-minus mr-1"></i>
                    {{ now()->year - 1 }} toplam
                </p>
            </div>
        </div>
        @endif
    </div>


    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant']))
        <!-- Recent Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                        Son Eklenen Üyeler
                    </h3>
                    <a href="{{ route('admin.members.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Tümünü Gör
                    </a>
                </div>
            </div>
            <div class="p-4">
                @if($recent_members->count() > 0)
                    <div class="space-y-3">
                        @foreach($recent_members->take(3) as $member)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-xs">{{ substr($member->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $member->name }} {{ $member->surname }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-900">{{ number_format($member->monthly_dues, 2) }} €</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $member->status_text }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-users text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500 text-sm">Henüz üye eklenmemiş.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Overdue Dues -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                        Gecikmiş Aidatlar
                    </h3>
                    <a href="{{ route('admin.dues.overdue') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                        Tümünü Gör
                    </a>
                </div>
            </div>
            <div class="p-4">
                @if($overdue_dues->count() > 0)
                    <div class="space-y-3">
                        @foreach($overdue_dues->take(3) as $due)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $due->member->name }} {{ $due->member->surname }}</p>
                                        <p class="text-xs text-gray-500">{{ $due->month_name }} {{ $due->year }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-medium text-red-600">{{ number_format($due->amount, 2) }} €</p>
                                    <p class="text-xs text-gray-500">{{ $due->due_date->diffForHumansTr() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-check-circle text-green-300 text-3xl mb-3"></i>
                        <p class="text-gray-500 text-sm">Gecikmiş aidat bulunmuyor.</p>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>


</div>
@endsection
