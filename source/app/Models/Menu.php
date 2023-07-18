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
}
