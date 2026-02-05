<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\PersonnelCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class BoardMemberController extends Controller
{
    public function index()
    {
        $boardMembers = BoardMember::with('category')->orderBy('sort_order')->get();
        $categories = PersonnelCategory::active()->ordered()->get();
        return view('admin.board-members.index', compact('boardMembers', 'categories'));
    }

    public function create()
    {
        $categories = PersonnelCategory::active()->ordered()->get();
        return view('admin.board-members.create', compact('categories'));
    }

    public function store(Request $request)
    {
        \Log::info('Store method called', [
            'has_file' => $request->hasFile('image'),
            'all_data' => $request->all(),
            'files' => $request->allFiles()
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio_tr' => 'nullable|string',
            'bio_de' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'category_id' => 'nullable|exists:personnel_categories,id'
        ]);

        $data = [
            'name' => $request->name,
            'title_tr' => $request->title_tr,
            'title_de' => $request->title_de,
            'bio_tr' => $request->bio_tr,
            'bio_de' => $request->bio_de,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'category_id' => $request->category_id
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/board-members'), $filename);
            $data['image_path'] = 'board-members/' . $filename;
        }

        BoardMember::create($data);

        // Clear cache
        Cache::forget('board_members');

        return redirect()->route('admin.board-members.index')
            ->with('success', 'Yönetim kurulu üyesi başarıyla eklendi.');
    }

    public function show(BoardMember $boardMember)
    {
        return view('admin.board-members.show', compact('boardMember'));
    }

    public function edit(BoardMember $boardMember)
    {
        $categories = PersonnelCategory::active()->ordered()->get();
        return view('admin.board-members.edit', compact('boardMember', 'categories'));
    }

    public function update(Request $request, BoardMember $boardMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio_tr' => 'nullable|string',
            'bio_de' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'title_tr' => $request->title_tr,
            'title_de' => $request->title_de,
            'bio_tr' => $request->bio_tr,
            'bio_de' => $request->bio_de,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'category_id' => $request->category_id
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($boardMember->image_path) {
                $oldPath = public_path('storage/' . $boardMember->image_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/board-members'), $filename);
            $data['image_path'] = 'board-members/' . $filename;
        }

        $boardMember->update($data);

        // Clear cache
        Cache::forget('board_members');

        return redirect()->route('admin.board-members.index')
            ->with('success', 'Yönetim kurulu üyesi başarıyla güncellendi.');
    }

    public function destroy(BoardMember $boardMember)
    {
        // Delete image
        if ($boardMember->image_path) {
            Storage::disk('public')->delete($boardMember->image_path);
        }

        $boardMember->delete();

        // Clear cache
        Cache::forget('board_members');

        return redirect()->route('admin.board-members.index')
            ->with('success', 'Yönetim kurulu üyesi başarıyla silindi.');
    }

    public function toggleStatus(BoardMember $boardMember)
    {
        $boardMember->update(['is_active' => !$boardMember->is_active]);

        // Clear cache
        Cache::forget('board_members');

        return response()->json([
            'success' => true,
            'is_active' => $boardMember->is_active
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:board_members,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            BoardMember::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        // Clear cache
        Cache::forget('board_members');

        return response()->json(['success' => true]);
    }
}

