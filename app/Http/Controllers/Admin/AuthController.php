<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = trim($request->username);
        $password = $request->password;

        // 1. Cek Default credentials (admin / adminLumina123 atau admin / admin)
        $isDefaultAdmin = ($username === 'admin' && in_array($password, ['adminLumina123', 'admin']))
                       || ($username === 'Lumina' && in_array($password, ['Lumina123', 'adminLumina123']));

        // 2. Cek database User jika ada
        $dbUser = User::where('email', $username)->orWhere('name', $username)->first();
        $isDbUser = $dbUser && Hash::check($password, $dbUser->password);

        if ($isDefaultAdmin || $isDbUser) {
            session([
                'admin_logged_in' => true,
                'admin_username'  => $isDbUser ? $dbUser->name : ucfirst($username),
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali di Management Console Grand Lumina Hotel!');
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password salah! (Gunakan default: admin / adminLumina123)');
    }

    public function logout(Request $request)
    {
        session()->forget(['admin_logged_in', 'admin_username']);
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar (logout) dari sesi Admin Console.');
    }
}
