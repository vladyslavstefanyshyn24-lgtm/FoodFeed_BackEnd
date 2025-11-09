<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    public function index()
    {
        $dishes = Dish::where('is_public', true)->latest()->withAvg('ratings', 'rating')->get();
        return response()->json($dishes);
    }

    public function show($id)
    {
        $dish = Dish::with('ratings')->withAvg('ratings', 'rating')->findOrFail($id);
        return response()->json($dish);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'is_public' => 'required|boolean'
        ]);

        $photo_url = null;
        if ($request->hasFile('photo')) {
            $photo_url = $request->file('photo')->store('dishes', 'public');
        }

        $dish = Dish::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'ingredients' => $request->ingredients,
            'photo_url' => $photo_url,
            'is_public' => $request->is_public,
        ]);

        return response()->json($dish, 201);
    }

    public function update(Request $request, $id)
    {
        $dish = Dish::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'ingredients' => 'sometimes|required|string',
            'photo' => 'nullable|image|max:2048',
            'is_public' => 'sometimes|required|boolean'
        ]);

        if ($request->hasFile('photo')) {
            if ($dish->photo_url) {
                Storage::disk('public')->delete($dish->photo_url);
            }
            $dish->photo_url = $request->file('photo')->store('dishes', 'public');
        }

        $dish->update($request->only(['title', 'ingredients', 'is_public']));

        return response()->json($dish);
    }

    public function destroy($id)
    {
        $dish = Dish::where('user_id', Auth::id())->findOrFail($id);
        if ($dish->photo_url) {
            Storage::disk('public')->delete($dish->photo_url);
        }
        $dish->delete();
        return response()->json(['message' => 'Dish deleted']);
    }
}
