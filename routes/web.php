<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\EducationMemberController;
use App\Http\Controllers\Admin\EducationPaymentController;
use App\Http\Controllers\Admin\DueController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DonationCertificateController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ThemeSettingsController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\QuickAccessController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\NewsPhotoController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BoardMemberController;
use App\Http\Controllers\Admin\PersonnelCategoryController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\GalleryCategoryController;
use App\Http\Controllers\Admin\GalleryImageController;
use App\Http\Controllers\Admin\VideoGalleryController;
use App\Http\Controllers\Admin\VideoCategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\MemberApplicationController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicPersonnelController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\VideoGalleryController as PublicVideoGalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\Admin\WhatsAppReminderController;
use App\Http\Controllers\Admin\TvDisplayController;
use App\Http\Controllers\Admin\TvDisplayMessageController;
use App\Http\Controllers\Admin\TvDisplaySettingsController;
// use App\Http\Controllers\Admin\LanguageManagementController;
use App\Http\Controllers\Admin\AdminArtisanController;
use App\Http\Controllers\EventDisplayController;
use App\Http\Controllers\VefaDisplayController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventAdvertisementController;
use App\Http\Controllers\Admin\VefaController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EmailLogController;
use App\Http\Controllers\Admin\PrayerTimeController;
use App\Http\Controllers\DeployController;
// use App\Http\Controllers\LanguageController;

// GitHub Webhook - EN BAŞTA, CSRF koruması olmadan (public route)
// Bu route'lar CSRF'den muaf olmalı çünkü GitHub webhook'ları CSRF token göndermez
Route::post('/webhook/deploy', [DeployController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
    ->middleware('throttle:10,1'); // Rate limiting: 10 istek/dakika

Route::get('/webhook/deploy', [DeployController::class, 'index'])->name('webhook.index');
Route::get('/webhook/deploy/test', [DeployController::class, 'test'])->name('webhook.test');

// Language switching routes
Route::get('/language/{locale}', function($locale) {
    // Geçerli dil kontrolü
    if (!in_array($locale, ['tr', 'de'])) {
        $locale = 'tr'; // Geçersizse varsayılan olarak Türkçe
    }
    
    // Session'a kaydet
    session(['locale' => $locale]);
    
    // Önceki sayfaya yönlendir veya ana sayfaya
    return redirect()->back()->with('locale_changed', $locale);
})->name('language.switch')->where('locale', 'tr|de');

Route::get('/', [PublicController::class, 'welcome'])->name('welcome');
Route::get('/ezan', [PublicController::class, 'ezan'])->name('ezan');

// Public content routes - moved to controller for better organization
Route::get('/duyurular', [PublicController::class, 'announcements'])->name('announcements.all');
Route::get('/duyuru/{id}', [PublicController::class, 'announcementDetail'])->name('announcements.detail')->where('id', '[0-9]+');
Route::get('/haberler', [PublicController::class, 'news'])->name('news.all');
Route::get('/haber/{id}', [PublicController::class, 'newsDetail'])->name('news.detail')->where('id', '[0-9]+');
Route::get('/yonetim-kurulu', [PublicController::class, 'boardMembers'])->name('board-members.index');
Route::get('/personel-kategori/{category}', [PublicPersonnelController::class, 'showCategory'])->name('personnel-category');
Route::get('/iletisim', [ContactController::class, 'index'])->name('contact.index');
Route::get('/ara', [PublicController::class, 'search'])->name('search');

// WhatsApp Routes - DEVRE DIŞI: WhatsApp Cloud API kullanımı şu an aktif değildir
// Route::post('/whatsapp/send', [WhatsAppController::class, 'sendMessage'])->name('whatsapp.send');
// Route::get('/whatsapp/test', function () {
//     return view('whatsapp.test');
// })->name('whatsapp.test');

// Admin: Announcement image delete
Route::middleware(['auth'])->group(function () {
    Route::delete('/admin/announcements/{announcement}/image', [AnnouncementController::class, 'removeImage'])
        ->name('admin.announcements.remove-image');
});

// Gallery Routes
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{slug}', [GalleryController::class, 'category'])->name('gallery.category');
Route::get('/galeri/{categorySlug}/{imageId}', [GalleryController::class, 'image'])->name('gallery.image')->where('imageId', '[0-9]+');

// Video Gallery Routes
Route::get('/video-galeri', [PublicVideoGalleryController::class, 'index'])->name('video-gallery.index');
Route::get('/video-galeri/{slug}', [PublicVideoGalleryController::class, 'category'])->name('video-gallery.category');

// Public page routes
Route::get('/sayfa/{slug}', [PublicController::class, 'page'])->name('page.show');


Route::middleware(['auth', 'verified', 'admin', 'update.last.login'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get("/db-update",[AdminArtisanController::class,"index"])->name("db-update");
    Route::post("/migrate",[AdminArtisanController::class,"migrate"])->name("migrate");
    Route::post("/optimize",[AdminArtisanController::class,"optimize"])->name("optimize");
    Route::post("/clear-cache",[AdminArtisanController::class,"clearCache"])->name("clear-cache");
    Route::post("/composer-update",[AdminArtisanController::class,"composerUpdate"])->name("composer-update");

    // Members
    Route::get('members/pending-applications', [MemberController::class, 'pendingApplications'])->name('members.pending-applications');
    Route::post('members/{id}/approve', [MemberController::class, 'approveApplication'])->name('members.approve');
    Route::post('members/{id}/reject', [MemberController::class, 'rejectApplication'])->name('members.reject');
    Route::post('members/{id}/resend-email', [MemberController::class, 'resendApprovalEmail'])->name('members.resend-email');
    Route::post('members/{id}/send-reminder', [MemberController::class, 'sendOverdueReminder'])->name('members.send-reminder');
    Route::post('members/send-bulk-reminders', [MemberController::class, 'sendBulkOverdueReminders'])->name('members.send-bulk-reminders');

    // Excel Import Routes
    Route::get('members/import/form', [MemberController::class, 'showImportForm'])->name('members.import.form');
    Route::post('members/import', [MemberController::class, 'import'])->name('members.import');
    Route::get('members/template/download', [MemberController::class, 'downloadTemplate'])->name('members.template.download');

    // Labels Generation
    Route::post('members/generate-labels', [MemberController::class, 'generateLabels'])->name('members.generate-labels');
    // Envelopes Generation
    Route::post('members/generate-envelopes', [MemberController::class, 'generateEnvelopes'])->name('members.generate-envelopes');
    // Filtered Members PDF Generation
    Route::get('members/generate-filtered-pdf', [MemberController::class, 'generateFilteredMembersPdf'])->name('members.generate-filtered-pdf');
    // Select All Members
    Route::post('members/select-all', [MemberController::class, 'selectAll'])->name('members.select-all');

    // Specific member routes (must be before resource)
    Route::get('members/deleted', [MemberController::class, 'deleted'])->name('members.deleted');
    Route::post('members/{id}/restore', [MemberController::class, 'restore'])->name('members.restore');
    Route::delete('members/{id}/force-delete', [MemberController::class, 'forceDelete'])->name('members.force-delete');
    Route::post('members/{id}/generate-password', [MemberController::class, 'createPasswordForMember'])->name('members.generate-password');
    Route::post('members/deletion-requests/{id}/approve', [MemberController::class, 'approveDeletionRequest'])->name('members.deletion-requests.approve');
    Route::post('members/deletion-requests/{id}/reject', [MemberController::class, 'rejectDeletionRequest'])->name('members.deletion-requests.reject');
    Route::put('members/privacy-withdrawals/{id}/mark-notified', [MemberController::class, 'markPrivacyWithdrawalNotified'])->name('members.privacy-withdrawals.mark-notified');

    // Resource routes (must be after specific routes)
    Route::resource('members', MemberController::class);

    // Access Logs (DSGVO - Veri erişim logları) - Sadece super admin
    Route::get('access-logs', [\App\Http\Controllers\Admin\AccessLogController::class, 'index'])->name('access-logs.index');

    // Education Members
    Route::post('education-members/generate-annual-dues', [EducationMemberController::class, 'generateAnnualDues'])->name('education-members.generate-annual-dues');
    Route::get('education-members/export', [EducationMemberController::class, 'export'])->name('education-members.export');
    Route::post('education-dues/{educationDue}/mark-paid', [EducationMemberController::class, 'markPaid'])->name('education-dues.mark-paid');
    Route::get('education-members/import', [EducationMemberController::class, 'import'])->name('education-members.import');
    Route::get('education-members/template', [EducationMemberController::class, 'downloadTemplate'])->name('education-members.template');
    Route::post('education-members/import', [EducationMemberController::class, 'processImport'])->name('education-members.import.process');
    Route::resource('education-members', EducationMemberController::class);

    // Education Payments
    Route::get('education-payments/bulk', [EducationPaymentController::class, 'bulkPayment'])->name('education-payments.bulk');
    Route::post('education-payments/bulk', [EducationPaymentController::class, 'processBulkPayment'])->name('education-payments.bulk.process');
    Route::resource('education-payments', EducationPaymentController::class)->only(['index']);
    Route::delete('education-payments/{id}', [EducationPaymentController::class, 'destroy'])->name('education-payments.destroy');

    // Dues
    Route::get('dues/overdue', [DueController::class, 'overdue'])->name('dues.overdue');
    Route::post('dues/select-all-pages', [DueController::class, 'selectAllPages'])->name('dues.select-all-pages');
    Route::post('dues/bulk-create', [DueController::class, 'bulkCreate'])->name('dues.bulk-create');
    Route::post('dues/bulk-payment', [DueController::class, 'bulkPayment'])->name('dues.bulk-payment');
    Route::post('dues/generate-monthly', [DueController::class, 'generateMonthly'])->name('dues.generate-monthly');
    // Aidat düzenleme kaldırıldı - sadece görüntüleme ve silme
    Route::get('dues', [DueController::class, 'index'])->name('dues.index');
    Route::get('dues/{due}', [DueController::class, 'show'])->name('dues.show');
    Route::delete('dues/{due}', [DueController::class, 'destroy'])->name('dues.destroy');

    // Receipt Generation (must be before resource routes)
    Route::get('payments/bulk-receipt', [PaymentController::class, 'generateBulkReceipt'])->name('payments.bulk-receipt');
    Route::get('payments/check-certificate', [PaymentController::class, 'checkExistingCertificate'])->name('payments.check-certificate');
    Route::get('donation-certificates', [DonationCertificateController::class, 'index'])->name('donation-certificates.index');
    Route::delete('donation-certificates/{donationCertificate}', [DonationCertificateController::class, 'destroy'])->name('donation-certificates.destroy');
    Route::get('payments/{payment}/receipt', [PaymentController::class, 'generateReceipt'])->name('payments.receipt');
    Route::get('payments/check-payments/{memberId}', [PaymentController::class, 'checkMemberPayments'])->name('payments.check-payments');

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::post('payments/bulk-payment', [PaymentController::class, 'bulkPayment'])->name('payments.bulk-payment');
    Route::post('payments/bulk-delete', [PaymentController::class, 'bulkDelete'])->name('payments.bulk-delete');
    Route::get('payments/unpaid-dues/{member}', [PaymentController::class, 'getUnpaidDues'])->name('payments.unpaid-dues');

    // Monthly Payments Management
    Route::get('monthly-payments', [PaymentController::class, 'monthlyPayments'])->name('monthly-payments');
    Route::post('monthly-payments/process', [PaymentController::class, 'processMonthlyPayments'])->name('monthly-payments.process');

    // Announcements
    Route::post('announcements/{announcement}/toggle-status', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');
    Route::resource('announcements', AnnouncementController::class);

    // News
    Route::post('news/{news}/toggle-status', [NewsController::class, 'toggleStatus'])->name('news.toggle-status');
    Route::resource('news', NewsController::class);

    // News Photos
    Route::delete('news/{news}/photos/{photo}', [NewsController::class, 'destroyPhoto'])->name('news.photos.destroy');

    // Quick Access
    Route::post('quick-access/{quickAccess}/toggle-status', [QuickAccessController::class, 'toggleStatus'])->name('quick-access.toggle-status');
    Route::resource('quick-access', QuickAccessController::class);

    // Menu Management
    Route::post('menu/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menu.toggle-status');
    Route::post('menu/{menu}/toggle-dropdown', [MenuController::class, 'toggleDropdown'])->name('menu.toggle-dropdown');
    Route::post('menu/reorder', [MenuController::class, 'reorder'])->name('menu.reorder');
    Route::resource('menu', MenuController::class)->except(['create']);

    // Pages Management
    Route::post('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');
    Route::post('pages/reorder', [PageController::class, 'reorder'])->name('pages.reorder');
    Route::post('upload-image', [PageController::class, 'uploadImage'])->name('upload-image');
    Route::resource('pages', PageController::class);

    // Personnel Categories Management
    Route::post('personnel-categories/{personnelCategory}/toggle-status', [PersonnelCategoryController::class, 'toggleStatus'])->name('personnel-categories.toggle-status');
    Route::resource('personnel-categories', PersonnelCategoryController::class);

    // Board Members Management (Personnel)
    Route::post('board-members/{boardMember}/toggle-status', [BoardMemberController::class, 'toggleStatus'])->name('board-members.toggle-status');
    Route::post('board-members/reorder', [BoardMemberController::class, 'reorder'])->name('board-members.reorder');
    Route::resource('board-members', BoardMemberController::class);

    // Elections Management
    Route::get('elections/{election}/print', [ElectionController::class, 'printHtml'])->name('elections.print-html');
    Route::get('elections/{election}/generate-pdf/{member}/{language?}', [ElectionController::class, 'generatePdf'])->name('elections.generate-pdf');
    Route::post('elections/{election}/generate-bulk-pdf', [ElectionController::class, 'generateBulkPdf'])->name('elections.generate-bulk-pdf');
    Route::resource('elections', ElectionController::class);

    // Din Gorevlileri Management

    // Gallery Management
    Route::post('gallery-categories/{galleryCategory}/toggle-status', [GalleryCategoryController::class, 'toggleStatus'])->name('gallery-categories.toggle-status');
    Route::post('gallery-categories/reorder', [GalleryCategoryController::class, 'reorder'])->name('gallery-categories.reorder');
    Route::resource('gallery-categories', GalleryCategoryController::class);

    Route::post('gallery-images/{galleryImage}/toggle-status', [GalleryImageController::class, 'toggleStatus'])->name('gallery-images.toggle-status');
    Route::post('gallery-images/reorder', [GalleryImageController::class, 'reorder'])->name('gallery-images.reorder');
    Route::post('gallery-images/bulk-upload', [GalleryImageController::class, 'bulkUpload'])->name('gallery-images.bulk-upload');
    Route::resource('gallery-images', GalleryImageController::class);

    // Video Gallery Management
    Route::post('video-gallery/{videoGallery}/toggle-status', [VideoGalleryController::class, 'toggleStatus'])->name('video-gallery.toggle-status');
    Route::resource('video-gallery', VideoGalleryController::class);

    // Video Categories Management
    Route::post('video-categories/{videoCategory}/toggle-status', [VideoCategoryController::class, 'toggleStatus'])->name('video-categories.toggle-status');
    Route::resource('video-categories', VideoCategoryController::class);

    // Admin Users Management (Super Admin only)
    Route::resource('admin-users', AdminUserController::class);

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Theme Settings
    Route::get('theme-settings', [ThemeSettingsController::class, 'index'])->name('theme-settings.index');
    Route::put('theme-settings', [ThemeSettingsController::class, 'update'])->name('theme-settings.update');

    // Email Templates
    Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('email-templates/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::put('email-templates/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('email-templates.update');
    Route::get('email-templates/{emailTemplate}/preview', [EmailTemplateController::class, 'preview'])->name('email-templates.preview');

    // Email Logs
    Route::get('email-logs', [EmailLogController::class, 'index'])->name('email-logs.index');
    Route::get('email-logs/batch/{batchId}', [EmailLogController::class, 'batch'])->name('email-logs.batch');
    Route::post('email-logs/clean', [EmailLogController::class, 'clean'])->name('email-logs.clean');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // 2FA Routes
    Route::post('profile/two-factor/enable', [ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
    Route::post('profile/two-factor/confirm', [ProfileController::class, 'confirmTwoFactor'])->name('profile.two-factor.confirm');
    Route::post('profile/two-factor/disable', [ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/detailed', [ReportController::class, 'detailed'])->name('reports.detailed');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/export-xlsx', [ReportController::class, 'exportXlsx'])->name('reports.export-xlsx');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // WhatsApp Reminders - DEVRE DIŞI: WhatsApp Cloud API kullanımı şu an aktif değildir
    // Route::get('whatsapp/due-reminders', [WhatsAppReminderController::class, 'index'])->name('whatsapp.due-reminders');
    // Route::post('whatsapp/send-reminders', [WhatsAppReminderController::class, 'sendReminders'])->name('whatsapp.send-reminders');
    // Route::post('whatsapp/send-bulk-reminders', [WhatsAppReminderController::class, 'sendBulkReminders'])->name('whatsapp.send-bulk-reminders');
    // Route::get('whatsapp/templates', [WhatsAppReminderController::class, 'getTemplates'])->name('whatsapp.templates');


        // TV Display
        Route::get('tv-display', [TvDisplayController::class, 'index'])->name('tv-display.index');

        // TV Display Messages
        Route::resource('settings/tv-display-messages', TvDisplayMessageController::class)->names([
            'index' => 'settings.tv-display-messages.index',
            'create' => 'settings.tv-display-messages.create',
            'store' => 'settings.tv-display-messages.store',
            'show' => 'settings.tv-display-messages.show',
            'edit' => 'settings.tv-display-messages.edit',
            'update' => 'settings.tv-display-messages.update',
            'destroy' => 'settings.tv-display-messages.destroy'
        ]);

        // TV Display Settings
        Route::get('settings/tv-display-settings', [TvDisplaySettingsController::class, 'index'])->name('settings.tv-display-settings.index');
        Route::put('settings/tv-display-settings', [TvDisplaySettingsController::class, 'update'])->name('settings.tv-display-settings.update');
        Route::get('settings/tv-display-settings/reset', [TvDisplaySettingsController::class, 'reset'])->name('settings.tv-display-settings.reset');

        // Language Management - Commented out until controller is created
        // Route::get('language', [LanguageManagementController::class, 'index'])->name('language.index');
        // Route::get('language/files', [LanguageManagementController::class, 'languageFiles'])->name('language.files');
        // Route::get('language/files/{locale}/{file}', [LanguageManagementController::class, 'editLanguageFile'])->name('language.edit-file');
        // Route::put('language/files/{locale}/{file}', [LanguageManagementController::class, 'updateLanguageFile'])->name('language.update-file');
        // Route::get('language/content/{type}', [LanguageManagementController::class, 'contentTranslations'])->name('language.content-translations');
        // Route::get('language/content/{type}/{id}', [LanguageManagementController::class, 'editContentTranslation'])->name('language.edit-content');
        // Route::put('language/content/{type}/{id}', [LanguageManagementController::class, 'updateContentTranslation'])->name('language.update-content');
        // Route::get('language/stats', [LanguageManagementController::class, 'translationStats'])->name('language.stats');

        // Events Management
        Route::resource('events', EventController::class);
        Route::resource('event-advertisements', EventAdvertisementController::class);

        // Vefa Management
        Route::resource('vefas', VefaController::class);

        // Prayer Times Management
        Route::get('prayer-times', [PrayerTimeController::class, 'index'])->name('prayer-times.index');
        Route::post('prayer-times/import', [PrayerTimeController::class, 'import'])->name('prayer-times.import');
        Route::delete('prayer-times/delete-all', [PrayerTimeController::class, 'deleteAll'])->name('prayer-times.delete-all');

        // Cache Management
        Route::post('/clear-cache', [AdminArtisanController::class, 'clearCache'])->name('clear-cache');
    });

// Disabled register route - redirect to member application
Route::get('/register', function () {
    return redirect()->route('member.application');
})->name('register');

// Member Application Routes
Route::get('/uyelik-basvuru', [MemberApplicationController::class, 'showApplicationForm'])->name('member.application');
Route::post('/uyelik-basvuru', [MemberApplicationController::class, 'storeApplication'])->name('member.application.store');
Route::get('/uyelik-basvuru/basarili/{id}', [MemberApplicationController::class, 'applicationSuccess'])->name('member.application.success')->where('id', '[0-9]+');
Route::get('/uyelik-basvuru/pdf/{id}', [MemberApplicationController::class, 'generatePdf'])->name('member.application.pdf')->where('id', '[0-9]+');

// Member Auth Routes
Route::get('/uye-giris', [MemberAuthController::class, 'showLoginForm'])->name('member.login');
Route::post('/uye-giris', [MemberAuthController::class, 'login'])->name('member.login.submit');
Route::post('/uye-cikis', [MemberAuthController::class, 'logout'])->name('member.logout');

// Member Password Reset Routes
Route::get('/uye-sifre-sifirla', [App\Http\Controllers\MemberPasswordResetController::class, 'showForgotPasswordForm'])->name('member.forgot-password');
Route::post('/uye-sifre-sifirla', [App\Http\Controllers\MemberPasswordResetController::class, 'sendResetLink'])->name('member.password.email');
Route::get('/uye-sifre-yenile/{token}', [App\Http\Controllers\MemberPasswordResetController::class, 'showResetPasswordForm'])->name('member.password.reset');
Route::post('/uye-sifre-yenile', [App\Http\Controllers\MemberPasswordResetController::class, 'resetPassword'])->name('member.password.update');

// Member Password Setup Routes
Route::get('/sifre-olustur', [App\Http\Controllers\MemberPasswordSetupController::class, 'showSetupForm'])->name('member.password.setup');
Route::post('/sifre-olustur', [App\Http\Controllers\MemberPasswordSetupController::class, 'setupPassword'])->name('member.password.setup.store');

// Member Panel Routes (require member session)
Route::middleware('member.auth')->prefix('uye-panel')->name('member.')->group(function () {
    Route::get('/', [MemberAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [MemberAuthController::class, 'profile'])->name('profile');
    Route::put('/profil', [MemberAuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/odemelerim', [MemberAuthController::class, 'payments'])->name('payments');
    Route::get('/iletisim-bagis', [MemberAuthController::class, 'contact'])->name('contact');
    Route::get('/uye-belgesi', [MemberAuthController::class, 'generateCertificate'])->name('certificate');
    Route::get('/makbuz/{payment}', [MemberAuthController::class, 'generateReceipt'])->name('receipt');
    Route::get('/basvuru-formu', [MemberAuthController::class, 'viewApplication'])->name('application.view');
    Route::get('/basvuru-formu/html', [MemberAuthController::class, 'viewApplicationHtml'])->name('application.html');
    Route::get('/verilerimi-indir/{format}', [MemberAuthController::class, 'exportData'])->name('data.export')->where('format', 'json|pdf');
    Route::post('/verilerimi-sil', [MemberAuthController::class, 'requestDeletion'])->name('data.deletion.request');
    Route::put('/gizlilik-riza-geri-cek', [MemberAuthController::class, 'withdrawPrivacyConsent'])->name('privacy.consent.withdraw');
    Route::put('/gizlilik-riza-ver', [MemberAuthController::class, 'givePrivacyConsent'])->name('privacy.consent.give');
});

// Public Display Routes
Route::get('/etkinlikler', [EventDisplayController::class, 'index'])->name('events.display');
Route::get('/api/etkinlikler', [EventDisplayController::class, 'api'])->name('events.api');
Route::get('/etkinlikler-liste', [PublicController::class, 'events'])->name('events.index');
Route::get('/vefa', [VefaDisplayController::class, 'index'])->name('vefas.display');
Route::get('/api/vefa', [VefaDisplayController::class, 'api'])->name('vefas.api');

require __DIR__.'/auth.php';
