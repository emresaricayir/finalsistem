<?php

namespace App\Http\Controllers;

use App\Models\PersonnelCategory;
use App\Models\BoardMember;
use Illuminate\Http\Request;

class PublicPersonnelController extends Controller
{
    public function showCategory(PersonnelCategory $category)
    {
        $personnel = $category->personnel()
            ->active()
            ->orderBy('sort_order', 'asc')
            ->get();

        // Yönetim kurulu sayfasındaki gibi sıralama mantığı
        $orgName = \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi');

        return view('public.personnel-category', compact('category', 'personnel', 'orgName'));
    }
}
