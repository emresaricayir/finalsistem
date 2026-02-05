{{-- Şifre Belirleme Sayfası / Passwort Erstellen Seite --}}
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Belirleme / Passwort Erstellen - {{ $settings['organization_name'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo {
            max-height: 60px;
            max-width: 60px;
            margin-bottom: 15px;
        }

        .logo-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-placeholder i {
            font-size: 30px;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .logo-placeholder div {
            font-size: 11px;
            color: #6c757d;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 25px;
        }

        .member-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .member-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .member-email {
            font-size: 13px;
            color: #6c757d;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #007bff;
        }

        .form-input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 3px;
        }

        .submit-btn {
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #6c757d;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #007bff;
        }

        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .alert {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px 15px;
            }

            .title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($settings['logo'])
                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-placeholder" style="display: none;">
                    <i class="fas fa-building"></i>
                    <div>LOGO YÜKLENEMEDİ</div>
                </div>
            @else
                <div class="logo-placeholder">
                    <i class="fas fa-building"></i>
                    <div>LOGO YOK</div>
                </div>
            @endif

            <h1 class="title">Şifre Belirleme / Passwort Erstellen</h1>
            <p class="subtitle">{{ $settings['organization_name'] }} üye paneli için şifrenizi belirleyin<br>
            Bestimmen Sie Ihr Passwort für das {{ $settings['organization_name'] }} Mitgliederpanel</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="member-info">
            <div class="member-name">{{ $member->name }} {{ $member->surname }}</div>
            <div class="member-email">{{ $member->email }}</div>
        </div>

        <form method="POST" action="{{ route('member.password.setup.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="password" class="form-label">Yeni Şifre / Neues Passwort</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-input @error('password') error @enderror"
                       required
                       minlength="6"
                       autocomplete="new-password">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Şifre Onayı / Passwort Bestätigung</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="form-input @error('password_confirmation') error @enderror"
                       required
                       minlength="6"
                       autocomplete="new-password">
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">
                🔐 Şifremi Belirle / Passwort Erstellen
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('member.login') }}">← Üye Giriş Sayfasına Dön / Zur Anmeldeseite zurück</a>
        </div>
    </div>

    <script>
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const submitBtn = document.querySelector('.submit-btn');

            function validatePasswords() {
                if (password.value !== passwordConfirmation.value) {
                    passwordConfirmation.setCustomValidity('Şifreler eşleşmiyor / Passwörter stimmen nicht überein');
                    passwordConfirmation.classList.add('error');
                } else {
                    passwordConfirmation.setCustomValidity('');
                    passwordConfirmation.classList.remove('error');
                }
            }

            password.addEventListener('input', validatePasswords);
            passwordConfirmation.addEventListener('input', validatePasswords);

            form.addEventListener('submit', function(e) {
                if (password.value !== passwordConfirmation.value) {
                    e.preventDefault();
                    alert('Şifreler eşleşmiyor! / Passwörter stimmen nicht überein!');
                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'İşleniyor... / Wird verarbeitet...';
            });
        });
    </script>
</body>
</html>
