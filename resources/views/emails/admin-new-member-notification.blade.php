<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Üye Kaydı</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3b82f6;
        }
        .header h1 {
            color: #3b82f6;
            margin: 0;
            font-size: 28px;
        }
        .header .subtitle {
            color: #6b7280;
            font-size: 16px;
            margin-top: 5px;
        }
        .content {
            margin-bottom: 30px;
        }
        .alert {
            background-color: #dbeafe;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
            margin: 20px 0;
        }
        .alert h3 {
            color: #1e40af;
            margin-top: 0;
            font-size: 18px;
        }
        .member-details {
            background-color: #f8fafc;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        .member-details h3 {
            color: #374151;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .detail-item {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #f3f4f6;
        }
        .detail-label {
            font-weight: 600;
            color: #4b5563;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: 500;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 15px 10px 15px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #2563eb;
        }
        .button.secondary {
            background-color: #6b7280;
        }
        .button.secondary:hover {
            background-color: #4b5563;
        }
        .actions {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .timestamp {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
            color: #4b5563;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .detail-grid {
                grid-template-columns: 1fr;
            }
            .button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏛️ {{ $organizationName }}</h1>
            <div class="subtitle">Yeni Üye Başvuru Bildirimi</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="alert">
                <h3>👤 Yeni Üye Başvuru Bildirimi</h3>
                <p>Sisteme yeni bir üye başvurusu yapılmıştır. Aşağıda başvuru detaylarını inceleyebilir ve onaylama işlemi yapabilirsiniz.</p>
            </div>

            <!-- Member Details -->
            <div class="member-details">
                <h3>📋 Üye Bilgileri</h3>

                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Üye No</div>
                        <div class="detail-value">{{ $member->member_no }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Durum</div>
                        <div class="detail-value">
                            @if($member->application_status === 'approved')
                                <span class="status-badge status-approved">Onaylandı</span>
                            @else
                                <span class="status-badge status-pending">Beklemede</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Ad</div>
                        <div class="detail-value">{{ $member->name }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Soyad</div>
                        <div class="detail-value">{{ $member->surname }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">E-posta</div>
                        <div class="detail-value">{{ $member->email }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Telefon</div>
                        <div class="detail-value">{{ $member->phone }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Doğum Tarihi</div>
                        <div class="detail-value">{{ $member->birth_date ? $member->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Cinsiyet</div>
                        <div class="detail-value">{{ $member->gender === 'male' ? 'Erkek' : ($member->gender === 'female' ? 'Kadın' : 'Belirtilmemiş') }}</div>
                    </div>

                    @if($member->address)
                    <div class="detail-item full-width">
                        <div class="detail-label">Adres</div>
                        <div class="detail-value">{{ $member->address }}</div>
                    </div>
                    @endif

                    @if($member->notes)
                    <div class="detail-item full-width">
                        <div class="detail-label">Notlar</div>
                        <div class="detail-value">{{ $member->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timestamp -->
            <div class="timestamp">
                <strong>📅 Kayıt Tarihi:</strong> {{ $member->created_at->format('d.m.Y H:i:s') }}
            </div>

            <!-- Actions -->
            <div class="actions">
                <h4 style="margin-top: 0; color: #374151;">🔧 Hızlı İşlemler</h4>
                <p style="color: #6b7280; margin-bottom: 20px;">Admin panelinden bu başvuru ile ilgili işlemler yapabilirsiniz:</p>

                <a href="{{ url('/admin/members/pending-applications') }}" class="button">
                    📋 Bekleyen Başvuruları Görüntüle
                </a>

                <a href="{{ url('/admin/members/' . $member->id) }}" class="button secondary">
                    👁️ Üye Detayını Görüntüle
                </a>

                <a href="{{ url('/admin/members') }}" class="button secondary">
                    📋 Tüm Üyeleri Görüntüle
                </a>
            </div>

            <!-- Additional Info -->
            <div style="background-color: #fef9e7; padding: 20px; border-radius: 8px; border-left: 4px solid #f59e0b; margin: 20px 0;">
                <h4 style="color: #92400e; margin-top: 0;">ℹ️ Bilgi</h4>
                <ul style="color: #78350f; margin: 0; padding-left: 20px;">
                    @if($member->application_status === 'approved')
                        <li>Bu üye otomatik olarak onaylanmış ve üye paneline giriş yapabilir durumda.</li>
                        <li>Üyeye onay maili otomatik olarak gönderilmiştir.</li>
                    @else
                        <li>Bu üye henüz onaylanmamış durumda.</li>
                        <li>Admin panelinden onaylama işlemi yapmanız gerekmektedir.</li>
                        <li>Başvuru PDF'ini inceleyebilir ve gerekirse düzenleyebilirsiniz.</li>
                    @endif
                    <li>Üye onaylandıktan sonra otomatik olarak aidat kayıtları oluşturulacaktır.</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Bu e-posta otomatik olarak gönderilmiştir.</p>
            <p>{{ $organizationName }} - Üyelik Sistemi</p>
            <p>© {{ date('Y') }} Tüm hakları saklıdır.</p>
        </div>
    </div>
</body>
</html>
