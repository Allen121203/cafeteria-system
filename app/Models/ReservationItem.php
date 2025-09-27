<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    protected $fillable = ['reservation_id','menu_id','quantity'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
