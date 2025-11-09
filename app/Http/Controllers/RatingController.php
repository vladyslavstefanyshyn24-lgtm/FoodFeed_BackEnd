<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'dish_id' => $request->dish_id],
            ['rating' => $request->rating]
        );

        return response()->json($rating, 201);
    }

    public function update(Request $request, $id)
    {
        $rating = Rating::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating->update(['rating' => $request->rating]);

        return response()->json($rating);
    }
    public function stats($dishId)
    {
        $ratings = Rating::where('dish_id', $dishId);

        $average = $ratings->avg('rating');       
        $count   = $ratings->count();             

        return response()->json([
            'dish_id' => $dishId,
            'average_rating' => round($average, 2),
            'ratings_count' => $count,
        ]);
    }

}
