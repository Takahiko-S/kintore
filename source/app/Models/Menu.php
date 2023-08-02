<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Menu extends Model
{
    use HasFactory;

    public function menuExercises()
    {
        return $this->hasMany(MenuExercise::class, 'menu_id');
    }
    public function exercises()
    {
        return $this->belongsToMany(Exercises::class, 'menu_exercises', 'menu_id', 'exercise_id');
    }
    // In your Menu model

    public function isCompleted($userId)
    {
        return $this->menuExercises()->whereHas('histories', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('is_completed', true);
        })->count() == $this->menuExercises()->count();
    }
}
