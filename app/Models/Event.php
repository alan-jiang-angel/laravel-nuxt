<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'date_time', 'location', 'link'];
    protected $dates = ['date_time', 'created_at', 'updated_at'];
}
