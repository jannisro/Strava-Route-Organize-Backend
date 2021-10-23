<?php

namespace App\Main\Util;

use Symfony\Component\HttpFoundation\Response;

class ResponseFactory {


    public static function makeJsonResponse(int $status, array $content): Response
    {
        return (new Response(
            json_encode($content),
            $status,
            ['content-type' => 'application/json']
        ))->send();
    }


}