<?php

namespace App\Packages\EnvUpdater;

class EnvUpdater
{
    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();

        $str = file_get_contents($envFile);

        $oldValue = env($envKey);

        if ($oldValue) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str = $str . "{$envKey}={$envValue}\n";
        }

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

    public static function delEnvironmentValue($envKey)
    {
        $envFile = app()->environmentFilePath();

        $str = file_get_contents($envFile);

        $oldValue = env($envKey);

        if ($oldValue) {
            $str = str_replace("{$envKey}={$oldValue}", "", $str);
        } else {
            return ;
        }

        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }
}
