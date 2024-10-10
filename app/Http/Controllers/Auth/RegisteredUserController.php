<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Cart;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return response()->json([
            'success' => true,
            'message' => 'Registration view is not available in API mode.'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            $customer = new Customer();
            $names = explode(" ", $user->name);
            $customer->user_id = $user->id;
            $customer->first_name = $names[0];
            $customer->last_name = $names[1] ?? '';
            $customer->save();

            Auth::login($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Unable to register right now.',
                'error' => $e->getMessage()
            ], 500);
        }

        DB::commit();

        Cart::moveCartItemsIntoDb();

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'redirect_url' => RouteServiceProvider::HOME
        ]);
    }
}
