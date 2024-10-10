<?php

namespace App\Http\Controllers\Auth;

use App\Enums\CustomerStatus;
use App\Helpers\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return response()->json([
            'success' => true,
            'message' => 'Login view is not available in API mode.'
        ]);
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();
        Cart::moveCartItemsIntoDb();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect_url' => RouteServiceProvider::HOME
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}
