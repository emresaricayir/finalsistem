<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show the public contact page
     */
    public function index()
    {
        $settings = [
            'organization_name' => Settings::get('organization_name'),
            'organization_subtitle' => Settings::get('organization_subtitle'),
            'organization_address' => Settings::get('organization_address'),
            'organization_phone' => Settings::get('organization_phone'),
            'organization_fax' => Settings::get('organization_fax'),
            'organization_email' => Settings::get('organization_email'),
            'facebook_url' => Settings::get('facebook_url'),
            'instagram_url' => Settings::get('instagram_url'),
            'twitter_url' => Settings::get('twitter_url'),
            'youtube_url' => Settings::get('youtube_url'),
            'whatsapp_number' => Settings::get('whatsapp_number'),
            'map_latitude' => Settings::get('map_latitude', '52.2025'),
            'map_longitude' => Settings::get('map_longitude', '8.2014'),
            'map_zoom' => Settings::get('map_zoom', '15'),
            // Banka bilgileri
            'bank_name' => Settings::get('bank_name'),
            'account_holder' => Settings::get('account_holder'),
            'bank_iban' => Settings::get('bank_iban'),
            'bank_bic' => Settings::get('bank_bic'),
            'bank_purpose' => Settings::get('bank_purpose'),
            'paypal_link' => Settings::get('paypal_link'),
        ];

        return view('contact.index', compact('settings'));
    }
}
