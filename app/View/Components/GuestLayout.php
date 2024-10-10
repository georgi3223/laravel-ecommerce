<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    public function render()
    {
        return response()->json([
            'success' => true,
            'message' => 'Guest layout component is not available in API mode.'
        ]);
    }
}