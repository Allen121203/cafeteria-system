<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id', 'date', 'time', 'guests', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ReservationItem::class);
    }
    public function scopeStatus($q, $status)
{
    if (in_array($status, ['pending','approved','declined'], true)) {
        $q->where('status', $status);
    }
    return $q;
}

}
