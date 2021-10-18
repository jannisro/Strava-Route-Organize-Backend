<?php

namespace App\Main\Auth;

use Symfony\Component\HttpFoundation\Response;

class GetInitialUrl {

    /**
     * Sends response with the initial oAuth URL
     */
    public function __invoke(): Response
    {
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            return $this->sendSuccessResponse($_GET['redirect']);
        }
        return $this->sendErrorResponse();
    }


    private function sendSuccessResponse(string $redirect): Response
    {
        $config = include(__DIR__ . '/../../../config/setup.php');
        $res = new Response(
            json_encode(['url' => "http://www.strava.com/oauth/authorize?client_id={$config->strava_app_id}&response_type=code&redirect_uri=$redirect&approval_prompt=force&scope=read"]),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        return $res->send();
    }


    private function sendErrorResponse(): Response
    {
        $res = new Response(
            '{message: "Please submit an URL for redirection"}',
            Response::HTTP_BAD_REQUEST,
            ['content-type' => 'application/json']
        );
        return $res->send();
    }

}