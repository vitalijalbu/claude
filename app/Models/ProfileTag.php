<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileTag extends Model
{
    protected $fillable = [
        'profile_id',
        'tag_id',
    ];
}
