@extends('admin.layouts.app')

@section('title', 'Yeni Üye Ekle')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                    Yeni Üye Ekle
                </h1>
                <p class="mt-2 text-gray-600">Sisteme yeni üye kaydı oluşturun ve otomatik aidat planı oluşturun.</p>
            </div>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl flex items-center transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.members.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <!-- Personal Information -->
            <div>
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Kişisel Bilgiler</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Adı / Vorname *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="surname" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Soyadı / Nachname *
                        </label>
                        <input type="text" name="surname" id="surname" value="{{ old('surname') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('surname')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-2 text-blue-600"></i>Cinsiyet / Geschlecht
                        </label>
                        <select name="gender" id="gender"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Seçiniz / Bitte wählen</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Erkek / Männlich</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Kadın / Weiblich</option>
                        </select>
                        @error('gender')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-blue-600"></i>Doğum Tarihi / Geburtsdatum *
                        </label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('birth_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_place" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Doğum Yeri / Geburtsort
                        </label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('birth_place')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-flag mr-2 text-blue-600"></i>Uyruğu / Staatsangehörigkeit
                        </label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('nationality')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2 text-blue-600"></i>Mesleği / Beruf
                        </label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('occupation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-blue-600"></i>Telefon
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>E-Mail *
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-home mr-2 text-blue-600"></i>Adres / Anschrift *
                    </label>
                    <textarea name="address" id="address" rows="3" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Membership Information -->
            <div>
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-id-card text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Üyelik Bilgileri</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-2 text-green-600"></i>Durum *
                        </label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="membership_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-check mr-2 text-green-600"></i>Üyelik Tarihi *
                        </label>
                        <input type="date" name="membership_date" id="membership_date" value="{{ old('membership_date', now()->format('Y-m-d')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('membership_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="monthly_dues" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-euro-sign mr-2 text-green-600"></i>Aylık Aidat (€) *
                        </label>
                        <input type="number" name="monthly_dues" id="monthly_dues" value="{{ old('monthly_dues', \App\Models\Settings::getMinimumMonthlyDues()) }}" step="0.01" min="{{ \App\Models\Settings::getMinimumMonthlyDues() }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="{{ \App\Models\Settings::getMinimumMonthlyDues() }}.00">
                        @error('monthly_dues')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method Selection -->
                    <div>
                        <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>Ödeme Yöntemi *
                        </label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Nakit</option>
                            <option value="bank_transfer" {{ old('payment_method', 'bank_transfer') == 'bank_transfer' ? 'selected' : '' }}>Banka Transferi</option>
                            <option value="lastschrift_monthly" {{ old('payment_method') == 'lastschrift_monthly' ? 'selected' : '' }}>Lastschrift (Aylık)</option>
                            <option value="lastschrift_semi_annual" {{ old('payment_method') == 'lastschrift_semi_annual' ? 'selected' : '' }}>Lastschrift (6 Aylık)</option>
                            <option value="lastschrift_annual" {{ old('payment_method') == 'lastschrift_annual' ? 'selected' : '' }}>Lastschrift (Yıllık)</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div>
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Ek Bilgiler</h3>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                              placeholder="Üye hakkında ek bilgiler, notlar veya özel durumlar...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Auto Dues Generation Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <i class="fas fa-info text-white text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Otomatik Sistem İşlemleri</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• <strong>10 yıllık aidat planı</strong> otomatik oluşturulacak</li>
                            <li>• <strong>Üye numarası</strong> otomatik atanacak (benzersiz numara garanti edilir)</li>
                            <li>• <strong>Şifre:</strong> Üyeye e-posta ile gönderilen linkten kendi şifresini oluşturacak</li>
                            <li>• <strong>Uygulama durumu:</strong> Onaylanmış olarak işaretlenecek</li>
                            <li>• <strong>Ödeme yöntemi:</strong> Seçtiğiniz yöntem olarak kaydedilecek</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Member Login Info -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <i class="fas fa-key text-white text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-green-900 mb-2">Üye Paneli Giriş Bilgileri</h4>
                        <div class="text-sm text-green-700 space-y-2">
                            <p><strong>Giriş Adresi:</strong> <code class="bg-white px-2 py-1 rounded">{{ url('/uye-giris') }}</code></p>
                            <p><strong>Kullanıcı Adı:</strong> Üyenin e-posta adresi</p>
                            <p><strong>Şifre Oluşturma:</strong> Üyeye e-posta ile gönderilen "Şifrenizi Oluşturun" linki ile kendi şifresini belirleyecek</p>
                            <p class="text-xs italic">ℹ️ Üyeye gönderilen onay e-postasında şifre oluşturma linki bulunmaktadır.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.members.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Üyeyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
