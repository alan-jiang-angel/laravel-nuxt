<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'pub_date', 'description', 'image', 'platform', 'link', 'views', 'likes',
        'comments', 'favs', 'created_at', 'updated_at'];

}
