<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('aktor')->user();
        return view('panitia.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('aktor')->user();

        $request->validate([
            'nama'    => 'required|string|max:100',
            'no_hp'   => 'nullable|string|max:20',
            'jurusan' => 'nullable|string|max:100',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $data = $request->only(['nama', 'no_hp', 'jurusan']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('avatars', 'public');
        }

        if ($request->new_password) {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required|string|min:8|confirmed',
            ]);
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }
            $data['password'] = Hash::make($request->new_password);
        }

        $user->update($data);
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
