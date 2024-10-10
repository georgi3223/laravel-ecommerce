<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordController extends Controller
{
    public function show()
    {
        return response()->json([
            'success' => true,
            'message' => 'Confirm password view is not available in API mode.'
        ]);
    }

    public function store(Request $request)
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return response()->json([
            'success' => true,
            'message' => 'Password confirmed successfully',
            'redirect_url' => RouteServiceProvider::HOME
        ]);
    }
}
