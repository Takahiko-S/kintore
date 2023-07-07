<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  
    public function menuExercises()
    {
        return $this->hasMany(MenuExercise::class, 'menu_id');
    }
}
