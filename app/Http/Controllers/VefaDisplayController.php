<?php

namespace App\Http\Controllers;

use App\Models\Vefa;
use Illuminate\Http\Request;

class VefaDisplayController extends Controller
{
    public function index(Request $request)
    {
        $vefas = Vefa::active()
            ->orderBySort()
            ->get();

        $organizationName = \App\Models\Settings::get('organization_name', '.....Derneği');

        // Success parametresi varsa sayfayı yenile
        if ($request->has('success')) {
            return view('vefas.display', compact('vefas', 'organizationName'))
                ->with('refresh', true);
        }

        return view('vefas.display', compact('vefas', 'organizationName'));
    }

    public function api()
    {
        $vefas = Vefa::active()
            ->orderBySort()
            ->get()
            ->map(function ($vefa) {
                return [
                    'id' => $vefa->id,
                    'title' => $vefa->title,
                    'description' => $vefa->description,
                    'image_url' => $vefa->image_url,
                    'image_alt' => $vefa->image_alt,
                    'display_duration' => $vefa->display_duration,
                    'sort_order' => $vefa->sort_order,
                    'is_active' => $vefa->is_active,
                    'birth_date' => $vefa->birth_date?->format('Y-m-d'),
                    'death_date' => $vefa->death_date?->format('Y-m-d'),
                    'hometown' => $vefa->hometown,
                    'burial_place' => $vefa->burial_place,
                    'created_at' => $vefa->created_at->toISOString(),
                    'updated_at' => $vefa->updated_at->toISOString(),
                ];
            });

        return response()->json($vefas);
    }
}
