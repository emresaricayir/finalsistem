@extends('admin.layouts.app')

@section('title', 'Yeni Yazı')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.elections.index') }}" class="hover:text-gray-700">Yazı Yönetimi</a>
                <span>/</span>
                <span>Yeni Yazı</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Yeni Yazı Oluştur</h1>
            <p class="text-gray-600">Türkçe ve Almanca yazı/davetiye oluşturun</p>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-lg rounded-lg">
            <form action="{{ route('admin.elections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
                @csrf

                <!-- Türkçe Başlık -->
                <div>
                    <label for="title_tr" class="block text-sm font-medium text-gray-700 mb-2">
                        Türkçe Başlık <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title_tr"
                           id="title_tr"
                           value="{{ old('title_tr') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title_tr') border-red-500 @enderror"
                           placeholder="Örn: 2025 Yılı Genel Kurul Toplantısı">
                    @error('title_tr')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Türkçe İçerik -->
                <div>
                    <label for="content_tr" class="block text-sm font-medium text-gray-700 mb-2">
                        Türkçe İçerik <span class="text-red-500">*</span>
                    </label>
                    <div class="rich-editor-container">
                        <div class="editor-toolbar">
                            <button type="button" class="editor-btn" data-command="bold" title="Kalın"><b>B</b></button>
                            <button type="button" class="editor-btn" data-command="italic" title="İtalik"><i>I</i></button>
                            <button type="button" class="editor-btn" data-command="underline" title="Altı Çizili"><u>U</u></button>
                            <span class="separator">|</span>
                            <button type="button" class="editor-btn" data-command="insertUnorderedList" title="Madde İşaretli Liste">• Liste</button>
                            <button type="button" class="editor-btn" data-command="insertOrderedList" title="Numaralı Liste">1. Liste</button>
                            <span class="separator">|</span>
                            <button type="button" class="editor-btn" data-command="justifyLeft" title="Sola Hizala">⬅</button>
                            <button type="button" class="editor-btn" data-command="justifyCenter" title="Ortala">⬌</button>
                            <button type="button" class="editor-btn" data-command="justifyRight" title="Sağa Hizala">➡</button>
                        </div>
                        <div contenteditable="true"
                             class="editor-content w-full px-3 py-2 border border-gray-300 rounded-b-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content_tr') border-red-500 @enderror"
                             data-target="content_tr"
                             style="min-height: 200px; max-height: 400px; overflow-y: auto;"
                             placeholder="Sayın üyemiz, yazı içeriğinizi buraya yazın...">{{ old('content_tr') }}</div>
                        <textarea name="content_tr" id="content_tr" style="display: none;">{{ old('content_tr') }}</textarea>
                    </div>
                    @error('content_tr')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Bu metin PDF'de formatlanarak görünecektir. Bold, italic gibi formatları kullanabilirsiniz.</p>
                </div>

                <!-- Almanca Başlık -->
                <div>
                    <label for="title_de" class="block text-sm font-medium text-gray-700 mb-2">
                        Almanca Başlık <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title_de"
                           id="title_de"
                           value="{{ old('title_de') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title_de') border-red-500 @enderror"
                           placeholder="z.B: Generalversammlung 2025">
                    @error('title_de')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Almanca İçerik -->
                <div>
                    <label for="content_de" class="block text-sm font-medium text-gray-700 mb-2">
                        Almanca İçerik <span class="text-red-500">*</span>
                    </label>
                    <div class="rich-editor-container">
                        <div class="editor-toolbar">
                            <button type="button" class="editor-btn" data-command="bold" title="Fett"><b>B</b></button>
                            <button type="button" class="editor-btn" data-command="italic" title="Kursiv"><i>I</i></button>
                            <button type="button" class="editor-btn" data-command="underline" title="Unterstrichen"><u>U</u></button>
                            <span class="separator">|</span>
                            <button type="button" class="editor-btn" data-command="insertUnorderedList" title="Aufzählung">• Liste</button>
                            <button type="button" class="editor-btn" data-command="insertOrderedList" title="Nummerierung">1. Liste</button>
                            <span class="separator">|</span>
                            <button type="button" class="editor-btn" data-command="justifyLeft" title="Links">⬅</button>
                            <button type="button" class="editor-btn" data-command="justifyCenter" title="Zentriert">⬌</button>
                            <button type="button" class="editor-btn" data-command="justifyRight" title="Rechts">➡</button>
                        </div>
                        <div contenteditable="true"
                             class="editor-content w-full px-3 py-2 border border-gray-300 rounded-b-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content_de') border-red-500 @enderror"
                             data-target="content_de"
                             style="min-height: 200px; max-height: 400px; overflow-y: auto;"
                             placeholder="Liebe/r Mitglied, schreiben Sie hier Ihren Text...">{{ old('content_de') }}</div>
                        <textarea name="content_de" id="content_de" style="display: none;">{{ old('content_de') }}</textarea>
                    </div>
                    @error('content_de')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Dieser Text wird formatiert im PDF angezeigt. Sie können Bold, Italic usw. verwenden.</p>
                </div>

                <!-- Başkan İmzası -->
                <div>
                    <label for="president_signature" class="block text-sm font-medium text-gray-700 mb-2">
                        Başkan İmzası (PNG)
                    </label>
                    <input type="file"
                           name="president_signature"
                           id="president_signature"
                           accept="image/png,image/jpg,image/jpeg"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('president_signature') border-red-500 @enderror">
                    @error('president_signature')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">PDF'de başkan imza bölümünde gösterilecek imza resmi.</p>
                </div>

                <!-- Sekreter İmzası -->
                <div>
                    <label for="secretary_signature" class="block text-sm font-medium text-gray-700 mb-2">
                        Sekreter İmzası (PNG)
                    </label>
                    <input type="file"
                           name="secretary_signature"
                           id="secretary_signature"
                           accept="image/png,image/jpg,image/jpeg"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('secretary_signature') border-red-500 @enderror">
                    @error('secretary_signature')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">PDF'de sekreter imza bölümünde gösterilecek imza resmi.</p>
                </div>

                <!-- Aktif Durumu -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Aktif
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Aktif seçimler PDF oluşturma için kullanılabilir.</p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.elections.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        İptal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                        Yazı Oluştur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Simple Rich Editor Styles & Script -->
<style>
    .rich-editor-container {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .editor-toolbar {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .editor-btn {
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
    }
    .editor-btn:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    .editor-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .separator {
        color: #d1d5db;
        margin: 0 4px;
    }
    .editor-content {
        outline: none;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.6;
    }
    .editor-content:empty:before {
        content: attr(placeholder);
        color: #9ca3af;
        pointer-events: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all rich editors
    document.querySelectorAll('.rich-editor-container').forEach(function(container) {
        const toolbar = container.querySelector('.editor-toolbar');
        const editor = container.querySelector('.editor-content');
        const textarea = container.querySelector('textarea');

        // Toolbar button clicks
        toolbar.addEventListener('click', function(e) {
            if (e.target.classList.contains('editor-btn')) {
                e.preventDefault();
                const command = e.target.dataset.command;

                editor.focus();
                document.execCommand(command, false, null);

                // Update button states
                updateButtonStates(toolbar, editor);

                // Update hidden textarea
                textarea.value = editor.innerHTML;
            }
        });

        // Update content on input
        editor.addEventListener('input', function() {
            textarea.value = editor.innerHTML;
            updateButtonStates(toolbar, editor);
        });

        // Update button states on selection change
        editor.addEventListener('selectionchange', function() {
            updateButtonStates(toolbar, editor);
        });

        // Load initial content
        if (textarea.value) {
            editor.innerHTML = textarea.value;
        }

        // Update button states
        function updateButtonStates(toolbar, editor) {
            toolbar.querySelectorAll('.editor-btn').forEach(function(btn) {
                const command = btn.dataset.command;
                if (document.queryCommandState(command)) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
    });
});
</script>
@endsection
