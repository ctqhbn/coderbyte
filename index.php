<?php

use Project\Utils;
use Project\Curl;

/**
 * Submit assessment to Coderbyte.
 */
function submitAssessment()
{
    $curl = new Curl();
    $tokenResponse = $curl->options('https://www.coredna.com/assessment-endpoint.php');

    $response = $curl->post(
        'https://www.coredna.com/assessment-endpoint.php',
        [
            'name' => 'Andrew Savetchuk',
            'email' => 'andrew.savetchuk@gmail.com',
            'url' => 'https://github.com/ctqhbn/coderbyte',
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
