<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'photo_url', 'ingredients',    'description', 'is_public'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }
}
