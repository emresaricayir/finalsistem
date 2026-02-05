@extends('admin.layouts.app')

@section('title', 'E-posta Şablonları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-envelope mr-2 text-blue-500"></i>
                E-posta Şablonları
            </h1>
            <p class="mt-2 text-gray-600">Sistem e-posta şablonlarını yönetin ve düzenleyin.</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Templates List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Şablon Adı
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Açıklama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Güncelleme
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($templates as $template)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-envelope text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $template->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $template->key }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $template->description ?: 'Açıklama yok' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($template->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Pasif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $template->updated_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.email-templates.preview', $template) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900 transition-colors"
                                       title="Önizleme">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.email-templates.edit', $template) }}"
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                       title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Henüz e-posta şablonu bulunmuyor</p>
                                    <p class="text-sm">E-posta şablonları otomatik olarak oluşturulacak.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">
                    E-posta Şablonları Hakkında
                </h3>
                <div class="text-sm text-blue-700 space-y-2">
                    <p>• E-posta şablonlarında <code>@{{ $variable_name }}</code> formatında değişkenler kullanabilirsiniz.</p>
                    <p>• Şablonları düzenledikten sonra "Önizleme" butonunu kullanarak sonucu kontrol edebilirsiniz.</p>
                    <p>• Pasif şablonlar sistem tarafından kullanılmaz.</p>
                    <p>• Değişiklikler anında etkili olur.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
