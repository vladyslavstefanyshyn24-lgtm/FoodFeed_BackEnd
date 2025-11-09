<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $dishes = Dish::where('is_public', true)
            ->orWhere('user_id', $userId)
            ->withAvg('ratings', 'rating')
            ->latest()
            ->paginate(20);

        return response()->json($dishes);
    }
}
