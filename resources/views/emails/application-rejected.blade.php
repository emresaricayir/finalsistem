<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üyelik Başvurunuz Reddedildi</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: block;
        }
        .organization-name {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
        }
        .title {
            font-size: 18px;
            color: #666;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .message {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .rejection-reason {
            background-color: #fff5f5;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
        }
        .reason-title {
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
        }
        .reason-text {
            color: #666;
            line-height: 1.5;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .contact-info strong {
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(\App\Models\Settings::hasLogo())
                <img src="{{ asset('storage/' . \App\Models\Settings::get('logo')) }}" alt="Logo" class="logo">
            @endif
            <div class="organization-name">{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}</div>
            <div class="title">Üyelik Başvurusu Reddedildi</div>
        </div>

        <div class="content">
            <div class="greeting">
                Sayın <strong>{{ $member->name }} {{ $member->surname }}</strong>,
            </div>

            <p>
                {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }} üyelik başvurunuzu değerlendirdik.
                Maalesef başvurunuz reddedilmiştir.
            </p>

            <div class="message">
                <strong>Üye Numarası:</strong> {{ $member->member_no }}<br>
                <strong>Başvuru Tarihi:</strong> {{ $member->created_at->format('d.m.Y H:i') }}
            </div>

            <div class="rejection-reason">
                <div class="reason-title">Red Sebebi:</div>
                <div class="reason-text">
                    {{ $rejectionReason }}
                </div>
            </div>

            <p>
                Eğer bu karar hakkında sorularınız varsa veya yeni bir başvuru yapmak isterseniz,
                aşağıdaki iletişim bilgilerinden bizimle iletişime geçebilirsiniz.
            </p>

            <div class="contact-info">
                @if(\App\Models\Settings::get('organization_email'))
                    <strong>E-posta:</strong> {{ \App\Models\Settings::get('organization_email') }}<br>
                @endif
                @if(\App\Models\Settings::get('organization_phone'))
                    <strong>Telefon:</strong> {{ \App\Models\Settings::get('organization_phone') }}<br>
                @endif
                @if(\App\Models\Settings::get('organization_address'))
                    <strong>Adres:</strong> {{ \App\Models\Settings::get('organization_address') }}
                @endif
            </div>
        </div>

        <div class="footer">
            <p>
                Bu e-posta {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }} tarafından gönderilmiştir.<br>
                © {{ date('Y') }} {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}. Tüm hakları saklıdır.
            </p>
        </div>
    </div>
</body>
</html>
