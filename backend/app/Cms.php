<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cms extends Model
{
    protected $table = 'cms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flag',
        'display_title',
        'original_content',
        'content',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
