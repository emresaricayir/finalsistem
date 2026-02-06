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

    <!-- Two-Factor Authentication -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-shield-alt mr-2 text-indigo-500"></i>
                İki Faktörlü Doğrulama (2FA)
            </h2>
        </div>

        <div class="p-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-3"></i>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($user->hasTwoFactorEnabled())
                <!-- 2FA Active -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-green-900 mb-2">2FA Aktif</h3>
                            <p class="text-green-800 text-sm mb-4">
                                İki faktörlü doğrulama aktif. Her girişte Google Authenticator kodunuz gerekecek.
                            </p>
                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                <p class="text-sm font-medium text-gray-700 mb-2">Aktif Edilme Tarihi:</p>
                                <p class="text-gray-900">{{ $user->two_factor_confirmed_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recovery Codes -->
                @if(session('recovery_codes'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                    <div class="flex items-start">
                        <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-yellow-900 mb-2">Recovery Kodları</h3>
                            <p class="text-yellow-800 text-sm mb-4">
                                Bu kodları güvenli bir yerde saklayın. Telefonunuzu kaybederseniz bu kodlarla giriş yapabilirsiniz.
                            </p>
                            <div class="bg-white rounded-lg p-4 border border-yellow-200 font-mono text-sm">
                                @foreach(session('recovery_codes') as $code)
                                    <div class="py-1">{{ $code }}</div>
                                @endforeach
                            </div>
                            <p class="text-yellow-800 text-xs mt-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                Bu kodlar sadece bir kez gösterilir. Lütfen kaydedin!
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Disable 2FA -->
                <form method="POST" action="{{ route('admin.profile.two-factor.disable') }}" onsubmit="return confirm('2FA\'yı devre dışı bırakmak istediğinizden emin misiniz?');">
                    @csrf
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-red-900 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            2FA'yı Devre Dışı Bırak
                        </h3>
                        <p class="text-red-800 text-sm mb-4">
                            Güvenlik için şifrenizi girmeniz gerekiyor.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label for="disable_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Şifre *
                                </label>
                                <input type="password" name="password" id="disable_password" required
                                       class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                       placeholder="Şifrenizi girin">
                            </div>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200">
                                <i class="fas fa-ban mr-2"></i>
                                2FA'yı Devre Dışı Bırak
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <!-- 2FA Not Active -->
                @if(session('two_factor_qr'))
                    <!-- QR Code Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">
                            <i class="fas fa-qrcode mr-2"></i>
                            2FA Kurulumu
                        </h3>
                        <p class="text-blue-800 text-sm mb-4">
                            1. Google Authenticator uygulamanızı açın<br>
                            2. QR kodu okutun veya aşağıdaki kodu manuel olarak girin<br>
                            3. Uygulamadan gelen 6 haneli kodu girin
                        </p>
                        
                        <div class="bg-white rounded-lg p-6 border border-blue-200 mb-4">
                            <div class="text-center mb-4">
                                @php
                                    $qrCodeUrl = session('two_factor_qr');
                                @endphp
                                <div class="inline-block p-4 bg-white rounded-lg">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrCodeUrl) !!}
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-2">Manuel Kod:</p>
                                <p class="font-mono text-lg font-bold text-gray-900 break-all">{{ session('two_factor_secret') }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.profile.two-factor.confirm') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="confirm_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    6 Haneli Güvenlik Kodu *
                                </label>
                                <input type="text" name="code" id="confirm_code" required
                                       maxlength="6" pattern="[0-9]{6}"
                                       class="w-full border-gray-300 rounded-xl px-4 py-3 text-center text-2xl font-mono tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="000000"
                                       autocomplete="one-time-code">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200">
                                <i class="fas fa-check-circle mr-2"></i>
                                Doğrula ve Aktif Et
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Enable 2FA Button -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-shield-alt text-indigo-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">2FA'yı Aktif Et</h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    İki faktörlü doğrulama ile hesabınızı daha güvenli hale getirin. Google Authenticator uygulaması ile QR kod okutarak kolayca aktif edebilirsiniz.
                                </p>
                                <form method="POST" action="{{ route('admin.profile.two-factor.enable') }}">
                                    @csrf
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200">
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        2FA'yı Aktif Et
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
