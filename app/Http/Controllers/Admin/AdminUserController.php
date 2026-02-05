<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled in routes
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $users = User::with('roles')->where('is_admin', true)->paginate(15);
        $roles = Role::all();

        return view('admin.admin-users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $roles = Role::all();
        return view('admin.admin-users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin kullanıcısı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $adminUser)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $adminUser->load('roles');
        return view('admin.admin-users.show', compact('adminUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $adminUser)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $roles = Role::all();
        $adminUser->load('roles');
        return view('admin.admin-users.edit', compact('adminUser', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $adminUser)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($adminUser->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $adminUser->update($data);
        $adminUser->roles()->sync($request->roles);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin kullanıcısı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $adminUser)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Prevent deleting the last super admin
        if ($adminUser->isSuperAdmin() && User::where('is_admin', true)->count() <= 1) {
            return redirect()->route('admin.admin-users.index')
                ->with('error', 'Son süper admin kullanıcısı silinemez.');
        }

        $adminUser->delete();

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin kullanıcısı başarıyla silindi.');
    }
}
