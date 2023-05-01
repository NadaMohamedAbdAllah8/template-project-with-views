<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        DB::beginTransaction();
        try {
            // Login
            if (Auth::guard('admin')->attempt(['email' => request('email'),
                'password' => request('password')])) {
                return redirect()->route('admin.admins.index')
                    ->with('success', 'Logged In Successfully');
            } else {
                return redirect()->route('admin.login')->with('error', 'Bad credentials');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.login')->with('error');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('admin.login')->with('success', 'Logged Out Successfully');
    }
}
