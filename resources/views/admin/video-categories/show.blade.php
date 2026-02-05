@extends('admin.layouts.app')

@section('title', $videoCategory->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-video mr-2"></i>
                        {{ $videoCategory->name }}
                    </h3>
                    <div>
                        <a href="{{ route('admin.video-categories.edit', $videoCategory) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i>
                            Düzenle
                        </a>
                        <a href="{{ route('admin.video-categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Geri
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5>Kategori Bilgileri</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-sm-3"><strong>Kategori Adı:</strong></div>
                                    <div class="col-sm-9">{{ $videoCategory->name }}</div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-3"><strong>Slug:</strong></div>
                                    <div class="col-sm-9"><code>{{ $videoCategory->slug }}</code></div>
                                </div>

                                @if($videoCategory->description)
                                <div class="row mt-2">
                                    <div class="col-sm-3"><strong>Açıklama:</strong></div>
                                    <div class="col-sm-9">{{ $videoCategory->description }}</div>
                                </div>
                                @endif

                                <div class="row mt-2">
                                    <div class="col-sm-3"><strong>Sıralama:</strong></div>
                                    <div class="col-sm-9">
                                        <span class="badge badge-secondary">{{ $videoCategory->sort_order }}</span>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-3"><strong>Durum:</strong></div>
                                    <div class="col-sm-9">
                                        <span class="badge {{ $videoCategory->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $videoCategory->is_active ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-3"><strong>Oluşturulma:</strong></div>
                                    <div class="col-sm-9">{{ $videoCategory->created_at->format('d.m.Y H:i') }}</div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-3"><strong>Güncellenme:</strong></div>
                                    <div class="col-sm-9">{{ $videoCategory->updated_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            @if($videoCategory->cover_image)
                                <div class="text-center">
                                    <h5>Kapak Resmi</h5>
                                    <img src="{{ asset('storage/' . $videoCategory->cover_image) }}"
                                         alt="{{ $videoCategory->name }}"
                                         class="img-thumbnail"
                                         style="max-width: 100%;">
                                </div>
                            @else
                                <div class="text-center">
                                    <h5>Kapak Resmi</h5>
                                    <div class="bg-light border rounded p-5">
                                        <i class="fas fa-video fa-3x text-muted"></i>
                                        <p class="text-muted mt-2">Kapak resmi yok</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Kategorideki Videolar ({{ $videoCategory->videos->count() }})</h5>
                            <a href="{{ route('admin.video-gallery.create', ['category_id' => $videoCategory->id]) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Yeni Video Ekle
                            </a>
                        </div>

                        @if($videoCategory->videos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Video</th>
                                            <th>Başlık</th>
                                            <th>Durum</th>
                                            <th>Sıralama</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($videoCategory->videos as $video)
                                            <tr>
                                                <td>
                                                    <img src="{{ $video->thumbnail_url }}"
                                                         alt="{{ $video->title }}"
                                                         class="rounded"
                                                         style="width: 60px; height: 40px; object-fit: cover;">
                                                </td>
                                                <td>
                                                    <strong>{{ $video->title }}</strong>
                                                    @if($video->description)
                                                        <br><small class="text-muted">{{ Str::limit($video->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $video->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                        {{ $video->is_active ? 'Aktif' : 'Pasif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">{{ $video->sort_order }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.video-gallery.edit', $video) }}"
                                                           class="btn btn-sm btn-warning" title="Düzenle">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger delete-video"
                                                                data-id="{{ $video->id }}"
                                                                data-title="{{ $video->title }}" title="Sil">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-video fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Bu kategoride henüz video bulunmuyor.</p>
                                <a href="{{ route('admin.video-gallery.create', ['category_id' => $videoCategory->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i>
                                    İlk Videoyu Ekle
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Video Confirmation Modal -->
<div class="modal fade" id="deleteVideoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video Sil</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong id="videoTitle"></strong> videosunu silmek istediğinizden emin misiniz?</p>
                <p class="text-danger"><small>Bu işlem geri alınamaz.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form id="deleteVideoForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Delete video
    $('.delete-video').click(function() {
        const videoId = $(this).data('id');
        const videoTitle = $(this).data('title');

        $('#videoTitle').text(videoTitle);
        $('#deleteVideoForm').attr('action', `/admin/video-gallery/${videoId}`);
        $('#deleteVideoModal').modal('show');
    });
});
</script>
@endpush
