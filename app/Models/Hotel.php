<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Hotel extends Model
{
    use HasFactory, FilterQueryString;

    protected $filters = ['address', 'between'];

    protected $fillable = ['name', 'address', 'total_rooms', 'rooms_left', 'stars', 'room_price', 'image'];
}
