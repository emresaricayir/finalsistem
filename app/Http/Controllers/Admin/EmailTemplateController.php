<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = EmailTemplate::orderBy('name')->get();

        return view('admin.email-templates.index', compact('templates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', compact('emailTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'html_content' => 'required|string',
            'text_content' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $emailTemplate->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'html_content' => $request->html_content,
            'text_content' => $request->text_content,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'E-posta şablonu başarıyla güncellendi.');
    }

    /**
     * Preview the email template
     */
    public function preview(EmailTemplate $emailTemplate)
    {
        // Create sample data for preview
        $sampleData = $this->getSampleData($emailTemplate->key);

        $rendered = $emailTemplate->render($sampleData);

        return response($rendered['html_content'])
            ->header('Content-Type', 'text/html');
    }

    /**
     * Get sample data for template preview
     */
    private function getSampleData($templateKey)
    {
        $sampleData = [
            'organization_name' => \App\Models\Settings::get('organization_name', 'Dernek Adı'),
            'settings' => [
                'organization_name' => \App\Models\Settings::get('organization_name', 'Dernek Adı'),
                'organization_address' => \App\Models\Settings::get('organization_address', 'Örnek Adres'),
                'organization_phone' => \App\Models\Settings::get('organization_phone', '+49 123 456 789'),
                'organization_email' => \App\Models\Settings::get('organization_email', 'info@dernek.com'),
            ],
        ];

        // Add template-specific sample data
        switch ($templateKey) {
            case 'member-welcome':
            case 'member-approval':
            case 'application-confirmation':
            case 'application-rejected':
            case 'admin-new-member-notification':
                $sampleData['member'] = (object) [
                    'name' => 'Ahmet',
                    'surname' => 'Yılmaz',
                    'full_name' => 'Ahmet Yılmaz',
                    'email' => 'ahmet.yilmaz@example.com',
                    'member_no' => 'Mitglied001',
                    'membership_date' => now(),
                    'monthly_dues' => 15.00,
                    'activation_token' => 'sample-token-123',
                ];
                break;

            case 'due-reminder':
                $sampleData['member'] = (object) [
                    'name' => 'Ahmet',
                    'surname' => 'Yılmaz',
                    'full_name' => 'Ahmet Yılmaz',
                ];
                $sampleData['due'] = (object) [
                    'month_name' => 'Ocak',
                    'year' => 2024,
                    'due_date' => now()->addDays(7),
                    'amount' => 15.00,
                ];
                $sampleData['totalOverdue'] = 0;
                break;

            case 'overdue-dues-reminder':
                $sampleData['member'] = (object) [
                    'name' => 'Ahmet',
                    'surname' => 'Yılmaz',
                    'full_name' => 'Ahmet Yılmaz',
                ];
                $sampleData['overdueDues'] = collect([
                    (object) ['month_name' => 'Aralık', 'year' => 2023, 'amount' => 15.00],
                    (object) ['month_name' => 'Ocak', 'year' => 2024, 'amount' => 15.00],
                ]);
                break;

            case 'password-reset':
                $sampleData['member'] = (object) [
                    'name' => 'Ahmet',
                    'surname' => 'Yılmaz',
                ];
                $sampleData['resetUrl'] = url('/sifre-sifirla?token=sample-token');
                break;
        }

        return $sampleData;
    }
}
