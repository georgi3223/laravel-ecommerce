<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    public function render()
    {
        return response()->json([
            'success' => true,
            'message' => 'App layout component is not available in API mode.'
        ]);
    }
}
