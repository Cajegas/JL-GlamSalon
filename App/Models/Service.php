<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
   protected $fillable = ['category', 'name', 'price', 'duration'];

   public function appointments()
    {
        return $this->belongsToMany(
            Appointment::class,
            'appointment_services'
        );
    }
}
