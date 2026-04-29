<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        // List only admins
        $admins = User::where('is_admin', true)->latest()->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->is_admin = true;
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('admin.admins.index')->with('success', 'Administrateur ajouté avec succès.');
    }

    public function destroy(User $admin): RedirectResponse
    {
        if ($admin->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Administrateur supprimé avec succès.');
    }
}
