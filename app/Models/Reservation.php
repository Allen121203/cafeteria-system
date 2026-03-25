<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'event_name',
        'event_date',
        'event_time',
        'number_of_persons',
        'special_requests',
        'status',
        'decline_reason',
        'payment_receipt_path',
        'payment_uploaded_at',
        // Legacy fields for backward compatibility
        'date',
        'time',
        'guests'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'payment_uploaded_at' => 'datetime',
    ];

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
