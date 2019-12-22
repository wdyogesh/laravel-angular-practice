<?php

namespace App\Packages\Logger;

use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\Crypt;

class Log
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public static function initialize()
    {
        $file = 'user_activity_'.Carbon::now()->format('Y-m-d').'.log';

        if (!Storage::disk('log')->exists($file))
        {
            Storage::disk('log')->put($file, '');
        }

        return $file;
    }

    /**
     *	Function to store log details
    *
    *	@param @var params as array,
    *
    *	@return detailed info about transaction as array.
    *
    *	Created By : RaHHuL | Created On : 31 Oct 2018
    **/
    public static function write($params)
    {
        $file_name = self::initialize();

        $content = "--------------------------------------------------------\n\n";
        $content = $content."Operation\t\t\t:\t\t".$params['operation']."\n";
        $content = $content."Status\t\t\t\t:\t\t".$params['status']."\n";
        $content = $content."Message\t\t\t\t:\t\t".$params['message']."\n";
        $content = $content."Request Details\t\t:\t\t".Crypt::encryptString(json_encode($params['request_details']))."\n";
        // $content = $content."IP Address\t\t\t:\t\t".$params['ip_address']."\n";
        $content = $content."Date Time\t\t\t:\t\t".Carbon::now()."\n";

        Storage::disk('log')->append($file_name, $content);
    }
}
