@extends('admin.layouts.app')

@section('title', 'Yazı Yönetimi')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Yazı Yönetimi</h1>
                <p class="text-gray-600">Yazı ve davetiyeleri yönetin, PDF oluşturun. Yazı için gerekli alanları Ayarlar kısmından doldurduğunuza emin olun.</p>
            </div>
            <a href="{{ route('admin.elections.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Yeni Yazı
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Elections Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($elections->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Başlık
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Oluşturulma
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durum
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                İmza
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                PDF İşlemleri
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($elections as $election)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $election->title_tr }}</div>
                                        @if($election->title_de)
                                            <div class="text-sm text-gray-600">{{ $election->title_de }}</div>
                                        @endif
                                        <div class="text-sm text-gray-500">
                                            Oluşturuldu: {{ $election->created_at->format('d.m.Y H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $election->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($election->is_active)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Pasif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($election->hasSignature())
                                        <div class="flex items-center">
                                            <img src="{{ $election->getSignatureUrl() }}"
                                                 alt="İmza"
                                                 class="h-8 w-auto border border-gray-300 rounded">
                                            <span class="ml-2 text-xs text-green-600">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-times text-red-500"></i> Yok
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.elections.print-html', ['election' => $election->id, 'language' => 'tr', 'per_page' => 200]) }}" target="_blank"
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs flex items-center">
                                            <i class="fas fa-print mr-1"></i>
                                            TR Yazdır (HTML)
                                        </a>
                                        <a href="{{ route('admin.elections.print-html', ['election' => $election->id, 'language' => 'de', 'per_page' => 200]) }}" target="_blank"
                                           class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-xs flex items-center">
                                            <i class="fas fa-print mr-1"></i>
                                            DE Yazdır (HTML)
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.elections.edit', $election) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.elections.destroy', $election) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Bu seçimi silmek istediğinizden emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-vote-yea text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz yazı bulunmuyor</h3>
                    <p class="text-gray-500 mb-4">İlk yazınızı oluşturmak için başlayın.</p>
                    <a href="{{ route('admin.elections.create') }}"
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Yeni Yazı Oluştur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<div id="pdfModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="absolute inset-0 flex items-center justify-center p-4" onclick="closePdfModal()">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md" onclick="event.stopPropagation()">
            <div class="px-5 py-4 border-b">
                <h3 class="text-lg font-semibold">Toplu PDF Oluştur</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="text-sm text-gray-700">
                    Çok sayıda üye için PDF hazırlanırken işlem birkaç dakika sürebilir. İşlem parça parça yapılır ve her parça ZIP olarak indirilir.
                </div>
                <form id="pdfForm" method="POST" data-base="{{ url('admin/elections') }}">
                    @csrf
                    <input type="hidden" name="language" id="pdfLang" value="tr">
                    <input type="hidden" name="election_id" id="pdfElectionId" value="">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Paket Boyutu</label>
                            <select name="per_page" id="pdfPerPage" class="w-full border rounded px-2 py-1 text-sm">
                                <option value="100">100</option>
                                <option value="200" selected>200</option>
                                <option value="300">300</option>
                                <option value="400">400</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Paket No</label>
                            <input type="number" name="batch" id="pdfBatch" value="1" min="1" class="w-full border rounded px-2 py-1 text-sm">
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-blue-50 text-blue-700 text-xs rounded">
                        Öneri: 200 kişilik paketler halinde (1, 2, 3...) indirip birleştirebilirsiniz.
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" class="px-4 py-2 bg-gray-200 rounded" onclick="closePdfModal()">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded" onclick="submitPdf(event)">Hazırla</button>
                    </div>
                </form>
                <div id="pdfProgress" class="hidden">
                    <div class="flex items-center space-x-2 text-sm text-gray-700">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>PDF hazırlanıyor... Lütfen bekleyin.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentElectionId = null;
function openPdfModal(electionId, lang){
    currentElectionId = electionId;
    document.getElementById('pdfElectionId').value = electionId;
    document.getElementById('pdfLang').value = lang || 'tr';
    const form = document.getElementById('pdfForm');
    const base = form.getAttribute('data-base');
    form.action = base + '/' + electionId + '/generate-bulk-pdf';
    document.getElementById('pdfModal').classList.remove('hidden');
}
function closePdfModal(){
    document.getElementById('pdfModal').classList.add('hidden');
}
function submitPdf(e){
    document.getElementById('pdfProgress').classList.remove('hidden');
}
</script>
@endsection
