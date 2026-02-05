@extends('admin.layouts.app')

@section('title', 'Namaz Vakitleri')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-mosque mr-2 text-teal-500"></i>
                Namaz Vakitleri
            </h1>
            <p class="mt-2 text-gray-600">Namaz vakitlerini yönetin ve Excel/CSV dosyasından içe aktarın.</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Import Statistics -->
    @if(session('import_stats'))
        @php
            $stats = session('import_stats');
        @endphp
        @if(!empty($stats['errors']))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
                <p class="font-semibold mb-2">İçe Aktarma Hataları:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($stats['errors'] as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    <!-- Import Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-file-upload mr-2 text-blue-500"></i>
            Excel/CSV Dosyasından İçe Aktar
        </h2>
        
        <form action="{{ route('admin.prayer-times.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Dosya Seç <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="file" id="file" required
                           accept=".xlsx,.xls,.csv,.txt"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Desteklenen formatlar: .xlsx, .xls, .csv, .txt (Maks: 10MB)</p>
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-semibold text-blue-900 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Excel Dosya Formatı:
                        </p>
                        <p class="text-xs text-blue-800 mb-2">
                            <a href="https://awqatsalah.com/" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline font-medium">
                                https://awqatsalah.com/
                            </a> sitesine girin, yıllık namaz vaktini indirip buraya direkt yükleyin.
                        </p>
                        <p class="text-xs text-blue-700 font-medium mt-2">
                            Beklenen kolonlar: Tarih, İmsak, Güneş, Öğle, İkindi, Akşam, Yatsı
                        </p>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Şehir <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city" id="city" required
                           value="{{ old('city') }}"
                           placeholder="Örn: Garbsen"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                        Bölge
                    </label>
                    <input type="text" name="region" id="region"
                           value="{{ old('region', 'Niedersachsen') }}"
                           placeholder="Örn: Niedersachsen"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('region') border-red-500 @enderror">
                    @error('region')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                        Ülke
                    </label>
                    <input type="text" name="country" id="country"
                           value="{{ old('country', 'ALMANYA') }}"
                           placeholder="Örn: ALMANYA"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('country') border-red-500 @enderror">
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-xl transition-colors duration-200 flex items-center">
                    <i class="fas fa-upload mr-2"></i>
                    İçe Aktar
                </button>
            </div>
        </form>
    </div>

    <!-- Delete All Button -->
    @if(isset($groupedByMonth) && !empty($groupedByMonth))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('admin.prayer-times.delete-all') }}" method="POST" 
                  onsubmit="return confirm('Tüm namaz vakitlerini silmek istediğinize emin misiniz? Bu işlem geri alınamaz!');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-3 rounded-xl transition-colors duration-200 flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Tümünü Sil
                </button>
            </form>
        </div>
    @endif

    <!-- Prayer Times by Month -->
    @if(isset($groupedByMonth) && !empty($groupedByMonth))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-teal-500"></i>
                Namaz Vakitleri (Ay Bazında)
            </h2>

            <!-- Month Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-2 overflow-x-auto" aria-label="Tabs">
                    @foreach($groupedByMonth as $monthKey => $monthData)
                        <button onclick="showMonth('{{ $monthKey }}')" 
                                id="tab-{{ $monthKey }}"
                                class="month-tab whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $loop->first ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            {{ $monthData['name'] }}
                            <span class="ml-2 bg-gray-100 text-gray-600 py-1 px-2 rounded-full text-xs">
                                {{ $monthData['count'] }}
                            </span>
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Month Content -->
            @foreach($groupedByMonth as $monthKey => $monthData)
                <div id="content-{{ $monthKey }}" class="month-content {{ $loop->first ? '' : 'hidden' }}">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gün</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İmsak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Güneş</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İkindi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akşam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yatsı</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Şehir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($monthData['data'] as $prayerTime)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($prayerTime->date)->format('d.m.Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $prayerTime->day_name ?? \Carbon\Carbon::parse($prayerTime->date)->locale('tr')->dayName }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($prayerTime->imsak)->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($prayerTime->gunes)->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($prayerTime->ogle)->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($prayerTime->ikindi)->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($prayerTime->aksam)->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($prayerTime->yatsi)->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $prayerTime->city }}
                                            @if($prayerTime->region)
                                                <span class="text-gray-400">({{ $prayerTime->region }})</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <i class="fas fa-mosque text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Henüz namaz vakti eklenmemiş</h3>
            <p class="text-gray-600 mb-6">Excel veya CSV dosyasından namaz vakitlerini içe aktararak başlayın.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function showMonth(monthKey) {
        // Hide all month contents
        document.querySelectorAll('.month-content').forEach(el => {
            el.classList.add('hidden');
        });

        // Remove active state from all tabs
        document.querySelectorAll('.month-tab').forEach(el => {
            el.classList.remove('border-teal-500', 'text-teal-600');
            el.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected month content
        document.getElementById('content-' + monthKey).classList.remove('hidden');

        // Add active state to selected tab
        const activeTab = document.getElementById('tab-' + monthKey);
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-teal-500', 'text-teal-600');
    }
</script>
@endpush
@endsection
