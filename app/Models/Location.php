<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps = false;
    protected $table = 'location';
    protected $fillable = [
        'name',
        'address',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
