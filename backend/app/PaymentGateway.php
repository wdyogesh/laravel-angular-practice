<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'merchant_id',
        'publishable_key',
        'secret_key',
        'live_api_key'
    ];

    protected $table = "gateway_details";
}
