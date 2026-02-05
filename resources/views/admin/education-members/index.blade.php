@extends('admin.layouts.app')

@section('title', 'Veliler')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">
                        <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>
                        Veliler
                    </h1>
                    <p class="text-slate-600">Velileri yönetin ve yıllık aidatlarını oluşturun</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.education-members.import') }}"
                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-excel mr-2"></i>
                        Excel İçe Aktar
                    </a>
                    <a href="{{ route('admin.education-members.export', request()->query()) }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-excel mr-2"></i>
                        Excel Rapor İndir
                    </a>
                    <button onclick="openGenerateDuesModal()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Yıllık Aidat Oluştur
                    </button>
                    <a href="{{ route('admin.education-members.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>
                        Yeni Veli Ekle
                    </a>
                </div>
            </div>

            <!-- Kullanım Rehberi -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-lg mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 mb-2">Kullanım Rehberi</h3>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p><span class="font-medium">1.</span> Önce veli ekleyiniz.</p>
                            <p><span class="font-medium">2.</span> Gerekli yıl seçilip "Yıllık Aidat Oluştur" ile Ocak–Aralık aidatlarını oluşturun. İşlem tekrarlanabilir; mevcut kayıtlar korunur, eksikler tamamlanır.</p>
                            <p><span class="font-medium">3.</span> Sonradan veli ekleseniz bile o yılın aidatları otomatik oluşturulur. Gerekirse tekrar "Yıllık Aidat Oluştur"u çalıştırabilirsiniz.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Toplam Veli</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalMembers }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Aktif Veli</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $activeMembers }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-pause-circle text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Pasif Veli</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $inactiveMembers }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
            <form method="GET" id="educationFilterForm" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Veli adı, soyadı, öğrenci adı, e-posta veya telefon ile ara..."
                               class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div class="flex gap-3">
                    <select name="year" class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @php $years = ($availableYears ?? collect([$year]))->toArray(); @endphp
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus-border-transparent">
                        <option value="">Tüm Durumlar</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                    </select>
                    <a href="{{ route('admin.education-members.index') }}"
                       class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Temizle
                    </a>
                </div>
            </form>
        </div>

        <!-- Members Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @if($members->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="hover:text-slate-700">
                                        Veli
                                        @if(request('sort') == 'name')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Öğrenci
                                </th>
                                @php
                                    $monthNames = [1=>'Oca',2=>'Şub',3=>'Mar',4=>'Nis',5=>'May',6=>'Haz',7=>'Tem',8=>'Ağu',9=>'Eyl',10=>'Eki',11=>'Kas',12=>'Ara'];
                                @endphp
                                @for($m=1; $m<=12; $m++)
                                    <th class="px-2 py-3 text-center text-[10px] font-medium text-slate-500 uppercase tracking-wider">{{ $monthNames[$m] }}</th>
                                @endfor
                                <th class="px-3 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    İşlemler
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($members as $member)
                            <tr class="odd:bg-cyan-50 even:bg-white hover:bg-cyan-100">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $member->full_name }}
                                    </div>
                                    @if($member->dues->count() > 0)
                                        @php $lastDue = $member->dues->sortByDesc('due_date')->first(); @endphp
                                        @if($lastDue)
                                            <div class="mt-1 text-xs text-slate-500">
                                                <span class="px-2 py-0.5 bg-slate-100 rounded border border-slate-200">{{ $lastDue->due_date->format('Y-m') }} aidat</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="mt-1 text-xs font-semibold text-red-600">Aidat oluşturulmadı</div>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $member->student_full_name }}</div>
                                </td>
                                @for($m=1; $m<=12; $m++)
                                    @php
                                        $due = $member->dues->firstWhere(function($d) use ($m) { return (int)$d->due_date->month === $m; });
                                    @endphp
                                    <td class="px-2 py-2 whitespace-nowrap text-center">
                                        @if($due)
                                            <input type="checkbox" class="due-toggle h-4 w-4 text-green-600 rounded focus:ring-green-500"
                                                   data-due-id="{{ $due->id }}"
                                                   data-mark-url="{{ url('/admin/education-dues/'.$due->id.'/mark-paid') }}"
                                                   data-unmark-url="{{ url('/admin/education-payments/'.$due->id) }}"
                                                   {{ $due->status === 'paid' ? 'checked' : '' }} />
                                        @else
                                            <span class="text-slate-300 text-xs">-</span>
                                        @endif
                                    </td>
                                @endfor
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.education-members.show', $member) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors"
                                           title="Detayları Görüntüle">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.education-members.destroy', $member) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Bu veliyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors"
                                                    title="Veliyi Sil">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($members->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $members->links() }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">Henüz veli eklenmemiş</h3>
                    <p class="text-slate-600 mb-6">
                        Velileri yönetmek için ilk veliyi ekleyin.
                    </p>
                    <a href="{{ route('admin.education-members.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>
                        Yeni Veli Ekle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Generate Annual Dues Modal -->
<div id="generateDuesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Yıllık Aidat Oluştur</h3>
            </div>
            <form action="{{ route('admin.education-members.generate-annual-dues') }}" method="POST">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Bilgi:</p>
                                <p><strong>{{ $year }}</strong> yılı için tüm aktif velilerin 12 aylık aidatları otomatik olarak oluşturulacak. Her ayın son günü vade tarihi olarak ayarlanacak.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium mb-1">Dikkat:</p>
                                <p>Bu işlem geri alınamaz. Eğer {{ $year }} yılı için aidatlar zaten varsa, işlem yapılmayacaktır.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeGenerateDuesModal()"
                            class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                        İptal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Aidat Oluştur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Auto submit on filters change
const filterForm = document.getElementById('educationFilterForm');
const yearSelect = filterForm.querySelector('select[name="year"]');
const statusSelect = filterForm.querySelector('select[name="status"]');
const searchInput = filterForm.querySelector('input[name="search"]');

function submitFilters() { filterForm.requestSubmit(); }

if (yearSelect) yearSelect.addEventListener('change', submitFilters);
if (statusSelect) statusSelect.addEventListener('change', submitFilters);

// Debounced search
let searchTimer;
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(submitFilters, 500);
    });
}

document.querySelectorAll('.due-toggle').forEach(cb => {
    cb.addEventListener('change', async function() {
        const markUrl = this.dataset.markUrl;
        const unmarkUrl = this.dataset.unmarkUrl;
        const checked = this.checked;
        try {
            if (checked) {
                const resp = await fetch(markUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        paid_date: new Date().toISOString().slice(0,10),
                        payment_method: 'cash',
                        notes: null
                    })
                });
                if (!resp.ok) throw new Error('Mark failed');
            } else {
                const resp = await fetch(unmarkUrl, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                if (!resp.ok) throw new Error('Unmark failed');
            }
        } catch (e) {
            this.checked = !checked; // revert
            alert('İşlem sırasında hata oluştu.');
        }
    });
});
function openGenerateDuesModal() {
    document.getElementById('generateDuesModal').classList.remove('hidden');
}

function closeGenerateDuesModal() {
    document.getElementById('generateDuesModal').classList.add('hidden');
}


// Close modal when clicking outside
document.getElementById('generateDuesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGenerateDuesModal();
    }
});

</script>
@endsection
