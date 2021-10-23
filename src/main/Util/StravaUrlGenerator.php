<?php

namespace App\Main\Util;

class StravaUrlGenerator {


    public static function oAuthStart(string $redirectUrl): string
    {
        $config = self::getConfig();
        return "http://www.strava.com/oauth/authorize?client_id={$config->strava_app_id}&response_type=code&redirect_uri=$redirectUrl&approval_prompt=force&scope=read";
    }


    private static function getConfig(): object
    {
        return include(__DIR__ . '/../../../config/setup.php');
    }


}