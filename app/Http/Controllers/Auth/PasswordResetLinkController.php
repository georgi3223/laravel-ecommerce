<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return response()->json([
            'success' => true,
            'message' => 'Forgot password view is not available in API mode.'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => __('A password reset link has been sent to your email.')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error sending password reset link',
            'errors' => ['email' => __($status)]
        ], 422);
    }
}
