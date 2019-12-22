<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api_keys extends Model
{
    protected $fillable = [
        'key_title',
        'key_value',
        'key_meta_id'
    ];
}
