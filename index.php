<?php

namespace Project;

require_once 'Project/Utils.php';
require_once 'Project/HTTP_Client.php';

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
            'name' => 'Sang Ha Ngoc',
            'email' => 'truongngoclinhtt12@gmail.com',
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
