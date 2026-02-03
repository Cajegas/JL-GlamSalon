<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service;

class Appointment extends Model
{
    protected $fillable = [
            'user_id',
            'appointment_date',
            'appointment_time',
            'total_price',
            'status'

        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'appointment_services'
        )->withPivot('price')
         ->withTimestamps(); 
    }
}
