@extends('admin.layouts.app')

@section('title', 'Üye Düzenle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-user-edit mr-2 text-blue-500"></i>
                Üye Düzenle
            </h1>
            <p class="mt-2 text-gray-600">{{ $member->full_name }} üyesinin bilgilerini güncelleyin.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.members.show', $member) }}" class="btn-secondary px-6 py-3 rounded-xl font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.members.update', $member) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Kişisel Bilgiler
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>
                            Vorname *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="Üye adı">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>
                            Nachname *
                        </label>
                        <input type="text" name="surname" id="surname" value="{{ old('surname', $member->surname) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('surname') border-red-500 @enderror"
                               placeholder="Üye soyadı">
                        @error('surname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-2 text-blue-500"></i>
                            Geschlecht
                        </label>
                        <select name="gender" id="gender"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gender') border-red-500 @enderror">
                            <option value="">Bitte wählen</option>
                            <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Männlich</option>
                            <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Weiblich</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-green-500"></i>
                            E-posta *
                            @if(str_contains($member->email, '{{\App\Models\Settings::getTemporaryEmailDomain()}}'))
                                <span class="ml-2 px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded-full">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Geçici Email
                                </span>
                            @endif
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror
                               @if(str_contains($member->email, '{{\App\Models\Settings::getTemporaryEmailDomain()}}')) border-amber-300 bg-amber-50 @endif"
                               placeholder="ornek@email.com">
                        @if(str_contains($member->email, '{{\App\Models\Settings::getTemporaryEmailDomain()}}'))
                            <p class="mt-1 text-xs text-amber-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Bu geçici bir email adresidir. Lütfen gerçek email adresini girin.
                            </p>
                        @endif
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-purple-500"></i>
                            Telefon
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $member->phone) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                               placeholder="+49 555 123 45 67">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="member_no" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-badge mr-2 text-orange-500"></i>
                            Üye Numarası
                            @if(auth()->user()->hasRole('super_admin'))
                                <span class="ml-2 text-xs text-orange-600 font-semibold">(Sadece Super Admin)</span>
                            @endif
                        </label>
                        
                        @if(auth()->user()->hasRole('super_admin'))
                            <!-- Super Admin için uyarı mesajı -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Uyarı:</strong> Bu numara bağış sertifikalarında ve belgelerde görünüyor. 
                                    Değiştirmek eski belgelerle tutarsızlık yaratabilir.
                                </p>
                            </div>
                            <input type="text" name="member_no" id="member_no" value="{{ old('member_no', $member->member_no) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('member_no') border-red-500 @enderror"
                                   placeholder="ÜYE001">
                        @else
                            <!-- Normal admin için readonly -->
                            <input type="text" name="member_no" id="member_no" value="{{ old('member_no', $member->member_no) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-100 cursor-not-allowed"
                                   placeholder="ÜYE001" readonly>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Üye numarasını sadece super admin değiştirebilir.
                            </p>
                        @endif
                        @error('member_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-red-500"></i>
                            Geburtsdatum
                        </label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}" lang="tr"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>
                            Geburtsort
                        </label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $member->birth_place) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('birth_place') border-red-500 @enderror"
                               placeholder="Geburtsort">
                        @error('birth_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag mr-2 text-purple-500"></i>
                            Staatsangehörigkeit
                        </label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $member->nationality) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nationality') border-red-500 @enderror"
                               placeholder="Staatsangehörigkeit">
                        @error('nationality')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2 text-orange-500"></i>
                            Beruf
                        </label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $member->occupation) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('occupation') border-red-500 @enderror"
                               placeholder="Beruf">
                        @error('occupation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                        Anschrift
                    </label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                              placeholder="Anschrift...">{{ old('address', $member->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Membership Information -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-id-card mr-2 text-green-500"></i>
                    Üyelik Bilgileri
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-2 text-blue-500"></i>
                            Durum *
                        </label>
                        <select name="status" id="status" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            @if($member->status === 'suspended')
                                <option value="suspended" selected>Askıya Alınmış (Mevcut Durum)</option>
                            @endif
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="membership_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                            Üyelik Tarihi *
                        </label>
                        <input type="date" name="membership_date" id="membership_date" value="{{ old('membership_date', $member->membership_date->format('Y-m-d')) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('membership_date') border-red-500 @enderror">
                        @error('membership_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="monthly_dues" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                            Aylık Aidat (€) *
                        </label>
                        <input type="number" name="monthly_dues" id="monthly_dues" value="{{ old('monthly_dues', $member->monthly_dues) }}" step="0.01" min="0" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('monthly_dues') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('monthly_dues')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2 text-purple-500"></i>
                            Ödeme Yöntemi
                        </label>
                        <select name="payment_method" id="payment_method"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="">Ödeme yöntemi seçin</option>
                            <option value="cash" {{ old('payment_method', $member->payment_method) == 'cash' ? 'selected' : '' }}>Nakit</option>
                            <option value="bank_transfer" {{ old('payment_method', $member->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Banka Transferi</option>
                            <option value="lastschrift_monthly" {{ old('payment_method', $member->payment_method) == 'lastschrift_monthly' ? 'selected' : '' }}>Lastschrift (Aylık)</option>
                            <option value="lastschrift_semi_annual" {{ old('payment_method', $member->payment_method) == 'lastschrift_semi_annual' ? 'selected' : '' }}>Lastschrift (6 Aylık)</option>
                            <option value="lastschrift_annual" {{ old('payment_method', $member->payment_method) == 'lastschrift_annual' ? 'selected' : '' }}>Lastschrift (Yıllık)</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>
                            Şifre
                            <span class="text-xs text-gray-500 ml-1">(Boş bırakılırsa değişmez)</span>
                        </label>
                        <input type="text" name="password" id="password"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Yeni şifre girin (boş bırakılabilir)">
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Şifre girilirse otomatik olarak hash'lenir ve kaydedilir. Boş bırakılırsa mevcut şifre korunur.
                        </p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                    Notizen
                </label>
                <textarea name="notes" id="notes" rows="4"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                          placeholder="Zusätzliche Notizen zum Mitglied...">{{ old('notes', $member->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- DSGVO Veri İşleme Onayı -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                        <i class="fas fa-lock text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">
                            🔒 Datenverarbeitungszustimmung
                        </h4>
                        <p class="text-sm text-gray-600 mb-4">
                            DSGVO gereği kişisel verilerinizin işlenmesi için onayınız gerekmektedir.
                        </p>
                        @if($member->privacy_consent && $member->privacy_consent_date)
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <strong>Zustimmung erteilt:</strong> {{ $member->privacy_consent_date->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        @else
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Keine Zustimmung:</strong> Bitte Zustimmung einholen.
                                </p>
                            </div>
                        @endif
                        <div class="flex items-start">
                            <input type="checkbox" name="privacy_consent" id="privacy_consent" value="1"
                                   class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                   {{ old('privacy_consent', $member->privacy_consent) ? 'checked' : '' }}>
                            <label for="privacy_consent" class="ml-3 text-sm text-gray-700">
                                Ich habe die <a href="/sayfa/datenschutz" target="_blank" class="text-blue-600 hover:text-blue-800 underline font-medium">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner personenbezogenen Daten zu.
                            </label>
                        </div>
                        @error('privacy_consent')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.members.show', $member) }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
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
</div>
@endsection
