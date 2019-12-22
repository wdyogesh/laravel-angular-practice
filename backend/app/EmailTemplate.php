<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmailTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'subject',
        'template',
        'tag_desc',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getTitleAttribute($value)
    {
        return ucwords(str_replace('_', ' ', $value));
    }

    public function getTagDescAttribute($value)
    {
        return  json_decode($value);
    }
}
