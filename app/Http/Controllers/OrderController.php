<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::withCount('items')
            ->where(['created_by' => $user->id])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function view(Order $order)
    {
        $user = \request()->user();
        if ($order->created_by !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission to view this order"
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}
