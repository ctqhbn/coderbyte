<?php

require_once 'Project/Utils.php';
require_once 'Project/Curl.php';

/**
 * Submit assessment to Coderbyte.
 */
function submitAssessment()
{
    $curl = new Curl();
    $url = "https://corednacom.corewebdna.com/assessment-endpoint.php";
    $tokenResponse = $curl->options($url);

    $response = $curl->post(
        $url,
        [
            'name' => 'Sang Ha Ngoc',
            'email' => 'truongngoclinhtt12@gmail.com',
            'url' => 'https://github.com/ctqhbn/coderbyte',
        ],
        [
            'Authorization' => 'Bearer ' . $tokenResponse->getBody(),
            'Content-Type' => 'application/json',
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
