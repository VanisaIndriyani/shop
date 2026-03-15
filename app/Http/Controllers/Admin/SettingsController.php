<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('admin.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $emailChanged = $validated['email'] !== $user->email;
        $wantsPasswordChange = (string) ($validated['new_password'] ?? '') !== '';

        if ($wantsPasswordChange) {
            if ((string) ($validated['current_password'] ?? '') === '') {
                return back()->withErrors(['current_password' => 'Password lama wajib diisi.'])->withInput();
            }
            if (!Hash::check((string) $validated['current_password'], (string) $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
            }
        }

        if ($emailChanged) {
            $user->email = $validated['email'];
        }

        if ($wantsPasswordChange) {
            $user->password = Hash::make((string) $validated['new_password']);
        }

        if ($emailChanged || $wantsPasswordChange) {
            $user->save();
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}

