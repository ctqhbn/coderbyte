<?php

use Project\Utils;
use Project\Curl;

/**
 * Submit assessment to Coderbyte.
 */
function submitAssessment()
{
    $tokenResponse = Curl::options('https://www.coredna.com/assessment-endpoint.php');

    $response = Curl::post(
        'https://www.coredna.com/assessment-endpoint.php',
        [
            'name' => 'Andrew Savetchuk',
            'email' => 'andrew.savetchuk@gmail.com',
            'url' => 'https://github.com/AndrewSavetchuk/php-http-client',
        ],
        [
            'Authorization' => 'Bearer ' . $tokenResponse->getBody(),
            'content-type' => 'application/json',
        ]
    );

    Utils::dumpResponse($response);
}

/**
 * Entry point.
 */
try {
    submitAssessment();
} catch (Exception $e) {
    Utils::dumpPretty($e);
}
