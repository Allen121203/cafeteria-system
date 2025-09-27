<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Menu.php
class Menu extends Model
{
    protected $fillable = ['name','description','price','meal_time','menu_type','day_no','number'];

    public function items(){ return $this->hasMany(MenuItem::class); }

    public function scopeMeal($q, $meal) {
        $allowed = ['breakfast','am_snacks','lunch','pm_snacks','dinner'];
        if (in_array($meal, $allowed, true)) $q->where('meal_time', $meal);
        return $q;
    }
}
