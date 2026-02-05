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
                            Ad *
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
                            Soyad *
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
                            Cinsiyet / Geschlecht
                        </label>
                        <select name="gender" id="gender"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gender') border-red-500 @enderror">
                            <option value="">Seçiniz / Bitte wählen</option>
                            <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Erkek / Männlich</option>
                            <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Kadın / Weiblich</option>
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
                        </label>
                        <input type="text" name="member_no" id="member_no" value="{{ old('member_no', $member->member_no) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('member_no') border-red-500 @enderror"
                               placeholder="ÜYE001">
                        @error('member_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-red-500"></i>
                            Doğum Tarihi
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
                            Doğum Yeri
                        </label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $member->birth_place) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('birth_place') border-red-500 @enderror"
                               placeholder="Doğum yeri">
                        @error('birth_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag mr-2 text-purple-500"></i>
                            Uyruk
                        </label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $member->nationality) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nationality') border-red-500 @enderror"
                               placeholder="Uyruk">
                        @error('nationality')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2 text-orange-500"></i>
                            Meslek
                        </label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $member->occupation) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('occupation') border-red-500 @enderror"
                               placeholder="Meslek">
                        @error('occupation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                        Adres
                    </label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                              placeholder="Üye adresi...">{{ old('address', $member->address) }}</textarea>
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
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                    Notlar
                </label>
                <textarea name="notes" id="notes" rows="4"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                          placeholder="Üye hakkında ek notlar...">{{ old('notes', $member->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
