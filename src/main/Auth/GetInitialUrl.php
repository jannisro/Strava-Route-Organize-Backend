<?php

namespace App\Main\Auth;

use Symfony\Component\HttpFoundation\Response;
use App\Main\Util\ResponseFactory;
use App\Main\Util\StravaUrlGenerator;

class GetInitialUrl {

    /**
     * Sends response with the initial oAuth URL
     */
    public function __invoke(): Response
    {
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            return $this->sendSuccessResponse($_GET['redirect']);
        }
        return ResponseFactory::makeJsonResponse(
            Response::HTTP_BAD_REQUEST,
            ['message' => 'Please submit an URL for redirection']
        );
    }


    private function sendSuccessResponse(string $redirect): Response
    {
        return ResponseFactory::makeJsonResponse(
            Response::HTTP_OK,
            ['url' => StravaUrlGenerator::oAuthStart($redirect)]
        );
    }

}