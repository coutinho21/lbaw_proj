<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'event';
    protected $fillables = [
        'name',
        'eventdate',
        'description',
        'price',
        'public',
        'opentojoin',
        'capacity',
        'id_user',
        'id_location'
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'joined', 'id_event', 'id_user');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
