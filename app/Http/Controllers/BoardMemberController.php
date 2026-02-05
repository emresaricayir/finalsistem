<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BoardMemberController extends Controller
{
    public function index()
    {
        $boardMembers = Cache::remember('board_members', 3600, function () {
            return BoardMember::active()->ordered()->get();
        });

        $orgName = \App\Models\Settings::get('organization_name', 'Organizasyon');

        return view('board-members.index', compact('boardMembers', 'orgName'));
    }
}

