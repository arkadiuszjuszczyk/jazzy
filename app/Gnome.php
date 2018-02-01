<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnome extends Model
{
    protected $fillable = [
        'name', 'age', 'strength'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
