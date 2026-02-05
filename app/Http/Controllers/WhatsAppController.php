<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        $result = $this->whatsapp->sendMessage(
            $request->phone,
            $request->message
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'WhatsApp mesajı gönderildi!',
                'phone' => $result['phone']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }
    }
}
