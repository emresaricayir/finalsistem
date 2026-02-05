@extends('admin.layouts.app')

@section('title', 'E-posta Şablonu Düzenle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-500"></i>
                E-posta Şablonu Düzenle
            </h1>
            <p class="mt-2 text-gray-600">{{ $emailTemplate->name }}</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.email-templates.preview', $emailTemplate) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-eye mr-2"></i>
                Önizleme
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.email-templates.update', $emailTemplate) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-blue-500"></i>
                        Şablon Adı *
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $emailTemplate->name) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-green-500"></i>
                        E-posta Konusu *
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject', $emailTemplate->subject) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                    Açıklama
                </label>
                <textarea name="description" id="description" rows="3"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $emailTemplate->description) }}</textarea>
            </div>

            <!-- Variables Info -->
            @if($emailTemplate->variables && count($emailTemplate->variables) > 0)
                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">
                        <i class="fas fa-code mr-2 text-orange-500"></i>
                        Kullanılabilir Değişkenler
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($emailTemplate->variables as $variable)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <code>@{{ ${{ $variable }} }}</code>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- HTML Content -->
            <div>
                <label for="html_content" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-code mr-2 text-red-500"></i>
                    HTML İçerik *
                </label>
                <textarea name="html_content" id="html_content" rows="20" required
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm">{{ old('html_content', $emailTemplate->html_content) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">
                    HTML formatında e-posta içeriği. Değişkenler için <code>@{{ $variable_name }}</code> formatını kullanın.
                </p>
            </div>

            <!-- Text Content -->
            <div>
                <label for="text_content" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-text mr-2 text-gray-500"></i>
                    Metin İçerik (Opsiyonel)
                </label>
                <textarea name="text_content" id="text_content" rows="10"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('text_content', $emailTemplate->text_content) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">
                    HTML desteklemeyen e-posta istemcileri için düz metin versiyonu.
                </p>
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $emailTemplate->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    <i class="fas fa-toggle-on mr-1 text-green-500"></i>
                    Şablonu aktif et
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.email-templates.index') }}"
                   class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Şablonu Kaydet
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-yellow-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-yellow-800 mb-2">
                    Düzenleme İpuçları
                </h3>
                <div class="text-sm text-yellow-700 space-y-2">
                    <p>• <strong>Değişkenler:</strong> <code>@{{ $member->name }}</code> gibi değişkenleri kullanabilirsiniz.</p>
                    <p>• <strong>HTML:</strong> CSS stilleri ve HTML etiketleri kullanabilirsiniz.</p>
                    <p>• <strong>Önizleme:</strong> Değişikliklerinizi kaydetmeden önce önizleme yapabilirsiniz.</p>
                    <p>• <strong>Güvenlik:</strong> Sadece güvenilir HTML içeriği kullanın.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-resize textarea
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
});
</script>
@endsection
