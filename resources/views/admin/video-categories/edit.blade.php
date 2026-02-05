@extends('admin.layouts.app')

@section('title', 'Video Kategorisi Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Video Kategorisi Düzenle
                    </h3>
                </div>

                <form action="{{ route('admin.video-categories.update', $videoCategory) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name_tr">Türkçe Kategori Adı <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name_tr') is-invalid @enderror"
                                           id="name_tr"
                                           name="name_tr"
                                           value="{{ old('name_tr', $videoCategory->name_tr) }}"
                                           required>
                                    @error('name_tr')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name_de">Almanca Kategori Adı</label>
                                    <input type="text"
                                           class="form-control @error('name_de') is-invalid @enderror"
                                           id="name_de"
                                           name="name_de"
                                           value="{{ old('name_de', $videoCategory->name_de) }}">
                                    @error('name_de')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text"
                                           class="form-control @error('slug') is-invalid @enderror"
                                           id="slug"
                                           name="slug"
                                           value="{{ old('slug', $videoCategory->slug) }}">
                                    <small class="form-text text-muted">Boş bırakılırsa kategori adından otomatik oluşturulur.</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description_tr">Türkçe Açıklama</label>
                                    <textarea class="form-control @error('description_tr') is-invalid @enderror"
                                              id="description_tr"
                                              name="description_tr"
                                              rows="4">{{ old('description_tr', $videoCategory->description_tr) }}</textarea>
                                    @error('description_tr')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description_de">Almanca Açıklama</label>
                                    <textarea class="form-control @error('description_de') is-invalid @enderror"
                                              id="description_de"
                                              name="description_de"
                                              rows="4">{{ old('description_de', $videoCategory->description_de) }}</textarea>
                                    @error('description_de')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cover_image">Kapak Resmi</label>

                                    @if($videoCategory->cover_image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $videoCategory->cover_image) }}"
                                                 alt="{{ $videoCategory->name_tr }}"
                                                 class="img-thumbnail"
                                                 style="max-width: 200px;">
                                            <p class="text-muted small mt-1">Mevcut kapak resmi</p>
                                        </div>
                                    @endif

                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('cover_image') is-invalid @enderror"
                                               id="cover_image"
                                               name="cover_image"
                                               accept="image/*">
                                        <label class="custom-file-label" for="cover_image">Yeni dosya seçin</label>
                                    </div>
                                    <small class="form-text text-muted">JPEG, PNG, JPG, GIF formatları desteklenir. Maksimum 2MB.</small>
                                    @error('cover_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sort_order">Sıralama</label>
                                    <input type="number"
                                           class="form-control @error('sort_order') is-invalid @enderror"
                                           id="sort_order"
                                           name="sort_order"
                                           value="{{ old('sort_order', $videoCategory->sort_order) }}"
                                           min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $videoCategory->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Güncelle
                        </button>
                        <a href="{{ route('admin.video-categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i>
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate slug from name
    $('#name_tr').on('input', function() {
        const name = $(this).val();
        const slug = name.toLowerCase()
            .replace(/ğ/g, 'g')
            .replace(/ü/g, 'u')
            .replace(/ş/g, 's')
            .replace(/ı/g, 'i')
            .replace(/ö/g, 'o')
            .replace(/ç/g, 'c')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');

        if ($('#slug').val() === '') {
            $('#slug').val(slug);
        }
    });

    // Custom file input
    $('.custom-file-input').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
@endpush



