<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationCertificate;
use App\Models\Member;
use Illuminate\Http\Request;

class DonationCertificateController extends Controller
{
    /**
     * Listeleme sayfası
     */
    public function index(Request $request)
    {
        $query = DonationCertificate::with(['member', 'createdBy']);

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $certificates = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $members = Member::orderBy('surname')->orderBy('name')->get(['id', 'name', 'surname']);

        return view('admin.donation-certificates.index', compact('certificates', 'members'));
    }

    /**
     * Kaydı sil
     */
    public function destroy(DonationCertificate $donationCertificate)
    {
        $donationCertificate->delete();

        return redirect()
            ->route('admin.donation-certificates.index')
            ->with('success', 'Spendenbescheinigung kaydı silindi.');
    }
}

