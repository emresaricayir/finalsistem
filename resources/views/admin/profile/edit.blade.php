@extends('admin.layouts.app')

@section('title', 'Profil Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">
            <i class="fas fa-user-cog mr-2"></i>
            Profil Ayarları
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-4 py-2 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri
        </a>
    </div>

    <!-- Profile Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-user mr-2 text-blue-500"></i>
                Profil Bilgileri
            </h2>
        </div>

        <form method="post" action="{{ route('admin.profile.update') }}" class="p-6 space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Ad Soyad *
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $user->name) }}"
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ad Soyad">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-green-500"></i>
                        E-posta *
                    </label>
                    <input type="email" name="email" id="email" required
                           value="{{ old('email', $user->email) }}"
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="ornek@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Güncelle
                </button>
            </div>
        </form>
    </div>

    <!-- Password Update -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-key mr-2 text-red-500"></i>
                Şifre Değiştir
            </h2>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-6">
            @csrf
            @method('put')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-orange-500"></i>
                        Mevcut Şifre *
                    </label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Mevcut şifrenizi girin">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key mr-2 text-green-500"></i>
                        Yeni Şifre *
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Yeni şifrenizi girin">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                        Şifre Tekrar *
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Şifrenizi tekrar girin">
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-blue-900 font-semibold mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Şifre Güvenliği
                </h4>
                <ul class="text-blue-800 text-sm space-y-1">
                    <li>• En az 8 karakter uzunluğunda olmalı</li>
                    <li>• Büyük ve küçük harf içermeli</li>
                    <li>• En az bir rakam içermeli</li>
                    <li>• Özel karakterler kullanmanız önerilir</li>
                </ul>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-key mr-2"></i>
                    Şifreyi Değiştir
                </button>
            </div>
        </form>
    </div>

    <!-- Account Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                Hesap Bilgileri
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Hesap Türü</span>
                            <p class="text-gray-900 font-semibold">Admin</p>
                        </div>
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shield-alt text-purple-600"></i>
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Kayıt Tarihi</span>
                            <p class="text-gray-900 font-semibold">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Son Giriş</span>
                            <p class="text-gray-900 font-semibold">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->setTimezone('Europe/Berlin')->format('d.m.Y H:i') }}
                                @else
                                    İlk giriş
                                @endif
                            </p>
                        </div>
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Durum</span>
                            <p class="text-green-600 font-semibold">Aktif</p>
                        </div>
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
