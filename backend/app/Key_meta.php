<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Key_meta extends Model
{
    protected $table = "key_meta";
    protected $fillable = [ 'title','title_key'];

    public function api_keys()
    {
        return $this->hasMany('App\Api_keys');
    }
}
