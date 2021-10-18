<?php

namespace App\Main\Auth;

use Symfony\Component\HttpFoundation\Response;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp;

class GetUserToken {


    private object $config;


    public function __construct()
    {
        $this->config = include(__DIR__ . '/../../../config/setup.php');
    }


    /**
     * Receives oAuth code, creates a long term access token and returns encrypted key
     */
    public function __invoke(array $params): Response
    {
        if (isset($params['code'], $params['scope'])) {
            return $this->requestAccessToken($params['code'], $params['scope']);
        }
        return $this->sendErrorResponse(
            Response::HTTP_BAD_REQUEST,
            'code and scope must be submitted'
        );
    }


    private function requestAccessToken(string $code, string $scope): Response
    {
        if (false !== strpos($scope, 'read')) {
            $client = new GuzzleHttp\Client();
            $response = $client->request(
                'POST',
                'https://www.strava.com/oauth/token',
                [
                    'body' => [
                        'client_id' => $this->config->strava_app_id,
                        'client_secret' => $this->config->strava_app_secret,
                        'code' => $code,
                        'grant_type' => 'authorization_code'
                    ]
                ]
            );
            return $this->validateApiResponse($response);
        }
        return $this->sendErrorResponse(
            Response::HTTP_EXPECTATION_FAILED, 
            'Invalid scope submitted'
        );
    }


    private function validateApiResponse(ResponseInterface $res): Response
    {
        if ($res->getStatusCode() === Response::HTTP_OK) {
            return $this->sendTokenResponse(json_decode($res->getBody()));
        }
        return $this->sendErrorResponse(
            Response::HTTP_EXPECTATION_FAILED,
            'The Strava API is not reachable or your submitted code is invalid'
        );
    }


    private function sendTokenResponse(array $resData): Response
    {
        if (isset(
            $resData['expires_at'], 
            $resData['access_token'],
            $resData['refresh_token'], 
            $resData['athlete']
        )) {
            return (
                new Response(
                    json_encode([
                        'token' => $this->getUserToken($resData),
                        'athlete' => $resData['athlete']
                    ]),
                    Response::HTTP_OK,
                    ['content-type' => 'application/json']
                )
            )->send();
        }
        return $this->sendErrorResponse(
            Response::HTTP_SERVICE_UNAVAILABLE,
            'Invalid response from the Strava API received'
        );
    }


    private function getUserToken(array $data): string
    {
        $content = [
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token']
        ];
        return openssl_encrypt(
            json_encode($content),
            $this->config->cipher_algo,
            $this->config->app_key
        );
    }


    private function sendErrorResponse(int $status, string $message): Response
    {
        return (
            new Response(
                json_encode('message', $message),
                $status,
                ['content-type' => 'application/json']
            )
        )->send();
    }

}