<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventContact extends Model
{
    use HasFactory;
    protected $fillable=[
        'contact_id',
        'event_id',
    ];
}
