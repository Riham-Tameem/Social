<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable=[
        'date',
        'description',
        'user_id',
        'name',
        'video',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function contacts(){
        return $this->belongsToMany(Contact::class);
    }
}
