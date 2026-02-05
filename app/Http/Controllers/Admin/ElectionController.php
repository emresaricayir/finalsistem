<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $elections = Election::orderByDate()->get();
        return view('admin.elections.index', compact('elections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.elections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_tr' => 'required|string|max:255',
            'title_de' => 'required|string|max:255',
            'content_tr' => 'required|string',
            'content_de' => 'required|string',
            'is_active' => 'boolean',
            'signature_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'president_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'secretary_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // HTML içeriğini temizle - XSS koruması için sadece güvenli tag'lara izin ver
        $allowedTags = '<p><div><br><b><strong><i><em><u><ul><ol><li><span><h1><h2><h3><h4><h5><h6>';
        
        $data = [
            'title_tr' => strip_tags($request->title_tr),
            'title_de' => strip_tags($request->title_de),
            'content_tr' => $this->sanitizeHtml($request->content_tr, $allowedTags),
            'content_de' => $this->sanitizeHtml($request->content_de, $allowedTags),
            'is_active' => $request->has('is_active')
        ];

        // Storage klasörünü oluştur
        $directory = public_path('storage/elections');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // İmza resmi yükleme (eski sistem - geriye uyumluluk için)
        if ($request->hasFile('signature_image')) {
            $signatureImage = $request->file('signature_image');
            $filename = 'signature_' . time() . '_' . uniqid() . '.' . $signatureImage->getClientOriginalExtension();
            $signatureImage->move($directory, $filename);
            $data['signature_image'] = $filename;
        }

        // Başkan imzası yükleme
        if ($request->hasFile('president_signature')) {
            $presidentSignature = $request->file('president_signature');
            $filename = 'president_signature_' . time() . '_' . uniqid() . '.' . $presidentSignature->getClientOriginalExtension();
            $presidentSignature->move($directory, $filename);
            $data['president_signature'] = $filename;
        }

        // Sekreter imzası yükleme
        if ($request->hasFile('secretary_signature')) {
            $secretarySignature = $request->file('secretary_signature');
            $filename = 'secretary_signature_' . time() . '_' . uniqid() . '.' . $secretarySignature->getClientOriginalExtension();
            $secretarySignature->move($directory, $filename);
            $data['secretary_signature'] = $filename;
        }

        Election::create($data);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Yazı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Election $election)
    {
        return view('admin.elections.show', compact('election'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Election $election)
    {
        $validator = Validator::make($request->all(), [
            'title_tr' => 'required|string|max:255',
            'title_de' => 'required|string|max:255',
            'content_tr' => 'required|string',
            'content_de' => 'required|string',
            'is_active' => 'boolean',
            'signature_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'president_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'secretary_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // HTML içeriğini temizle - XSS koruması için sadece güvenli tag'lara izin ver
        $allowedTags = '<p><div><br><b><strong><i><em><u><ul><ol><li><span><h1><h2><h3><h4><h5><h6>';
        
        $data = [
            'title_tr' => strip_tags($request->title_tr),
            'title_de' => strip_tags($request->title_de),
            'content_tr' => $this->sanitizeHtml($request->content_tr, $allowedTags),
            'content_de' => $this->sanitizeHtml($request->content_de, $allowedTags),
            'is_active' => $request->has('is_active')
        ];

        // Storage klasörünü oluştur
        $directory = public_path('storage/elections');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // İmza resmi yükleme (eski sistem - geriye uyumluluk için)
        if ($request->hasFile('signature_image')) {
            // Eski imza resmini sil
            if ($election->signature_image) {
                $oldImagePath = public_path('storage/elections/' . $election->signature_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $signatureImage = $request->file('signature_image');
            $filename = 'signature_' . time() . '_' . uniqid() . '.' . $signatureImage->getClientOriginalExtension();
            $signatureImage->move($directory, $filename);
            $data['signature_image'] = $filename;
        }

        // Başkan imzası yükleme
        if ($request->hasFile('president_signature')) {
            // Eski başkan imzasını sil
            if ($election->president_signature) {
                $oldImagePath = public_path('storage/elections/' . $election->president_signature);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $presidentSignature = $request->file('president_signature');
            $filename = 'president_signature_' . time() . '_' . uniqid() . '.' . $presidentSignature->getClientOriginalExtension();
            $presidentSignature->move($directory, $filename);
            $data['president_signature'] = $filename;
        }

        // Sekreter imzası yükleme
        if ($request->hasFile('secretary_signature')) {
            // Eski sekreter imzasını sil
            if ($election->secretary_signature) {
                $oldImagePath = public_path('storage/elections/' . $election->secretary_signature);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $secretarySignature = $request->file('secretary_signature');
            $filename = 'secretary_signature_' . time() . '_' . uniqid() . '.' . $secretarySignature->getClientOriginalExtension();
            $secretarySignature->move($directory, $filename);
            $data['secretary_signature'] = $filename;
        }

        $election->update($data);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Yazı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Election $election)
    {
        // Eski imza resmini sil (geriye uyumluluk için)
        if ($election->signature_image) {
            $imagePath = public_path('storage/elections/' . $election->signature_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Başkan imzasını sil
        if ($election->president_signature) {
            $imagePath = public_path('storage/elections/' . $election->president_signature);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Sekreter imzasını sil
        if ($election->secretary_signature) {
            $imagePath = public_path('storage/elections/' . $election->secretary_signature);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $election->delete();
        return redirect()->route('admin.elections.index')
            ->with('success', 'Yazı başarıyla silindi.');
    }

    /**
     * Generate PDF for single member
     */
    public function generatePdf(Election $election, Member $member, $language = 'tr')
    {
        $content = $language === 'de' ? $election->content_de : $election->content_tr;

        $pdf = PDF::loadView('pdf.election-invitation', [
            'election' => $election,
            'member' => $member,
            'content' => $content,
            'language' => $language
        ]);

        $filename = "secim-davetiye-{$member->member_number}-{$language}.pdf";
        return $pdf->download($filename);
    }

    /**
     * Generate PDFs for all members
     */
    public function generateBulkPdf(Election $election, Request $request)
    {
        // Uzun süren işlemler için limitleri artır
        @ini_set('memory_limit', '1536M');
        @set_time_limit(900);
        @ignore_user_abort(true);

        $language = $request->get('language', 'tr');
        $content = $language === 'de' ? $election->content_de : $election->content_tr;

        // Parti parti üretim (batch) parametreleri
        $perPage = (int) ($request->get('per_page', 200));
        if ($perPage < 25) { $perPage = 25; }
        if ($perPage > 500) { $perPage = 500; }
        $batch = (int) ($request->get('batch', 1));
        if ($batch < 1) { $batch = 1; }

        // ZIP dosyasını hazırlayın
        $zipFilename = "secim-davetiye-{$election->id}-{$language}-" . date('Y-m-d') . ".zip";
        $zipPath = storage_path('app/temp/' . $zipFilename);

        // temp klasörü yoksa oluştur
        $zipDir = dirname($zipPath);
        if (!is_dir($zipDir)) {
            mkdir($zipDir, 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->with('error', 'ZIP dosyası oluşturulamadı.');
        }

        // Üyeleri küçük parçalar halinde işleyin ve PDF'leri doğrudan ZIP'e ekleyin
        $query = Member::where('status', 'active')->orderBy('id');
        $total = (clone $query)->count();
        $totalBatches = max(1, (int) ceil($total / $perPage));
        if ($batch > $totalBatches) { $batch = $totalBatches; }

        $members = $query->skip(($batch - 1) * $perPage)->take($perPage)->get();

        foreach ($members as $member) {
            $pdf = Pdf::loadView('pdf.election-invitation', [
                'election' => $election,
                'member' => $member,
                'content' => $content,
                'language' => $language
            ]);

            $pdfBinary = $pdf->output();
            $filename = "secim-davetiye-{$member->member_number}-{$language}.pdf";
            $zip->addFromString($filename, $pdfBinary);
            unset($pdf);
        }

        $zip->close();

        // Dosya adında batch bilgisi
        $finalName = pathinfo($zipFilename, PATHINFO_FILENAME) . "-batch{$batch}-of-{$totalBatches}.zip";
        $finalPath = storage_path('app/temp/' . $finalName);
        // Yeniden adlandır (Windows'ta aynı klasörde taşır)
        @rename($zipPath, $finalPath);

        return response()->download($finalPath)->deleteFileAfterSend(true);
    }

    /**
     * Print-ready HTML for all members (paginated to avoid memory issues)
     */
    public function printHtml(Election $election, Request $request)
    {
        $language = $request->get('language', 'tr');
        $content = $language === 'de' ? $election->content_de : $election->content_tr;
        $time = $election->election_time ?? '19:00'; // Default time if not set

        $perPage = (int) ($request->get('per_page', 200));
        if ($perPage < 50) { $perPage = 50; }
        if ($perPage > 500) { $perPage = 500; }

        $members = Member::where('status', 'active')
    ->orderBy('surname')
    ->orderBy('name')
    ->paginate($perPage)
    ->appends($request->query());

        return view('admin.elections.print', compact('election', 'members', 'language', 'content', 'time'));
    }

    /**
     * HTML içeriğini temizle - XSS koruması
     * Sadece güvenli tag'lara izin ver ve event handler'ları temizle
     */
    private function sanitizeHtml($html, $allowedTags = '')
    {
        if (empty($html)) {
            return $html;
        }

        // Önce strip_tags ile sadece izin verilen tag'ları bırak
        $cleaned = strip_tags($html, $allowedTags);

        // Event handler'ları ve javascript: URL'lerini temizle
        $cleaned = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $cleaned);
        $cleaned = preg_replace('/\s*on\w+\s*=\s*[^\s>]*/i', '', $cleaned);
        $cleaned = preg_replace('/javascript:/i', '', $cleaned);
        $cleaned = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $cleaned);
        $cleaned = preg_replace('/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi', '', $cleaned);
        $cleaned = preg_replace('/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi', '', $cleaned);
        $cleaned = preg_replace('/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi', '', $cleaned);

        return $cleaned;
    }
}
