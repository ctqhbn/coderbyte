<?php

require_once 'Project/Utils.php';
require_once 'Project/Http.php';

/**
 * Submit assessment to Coderbyte.
 */
function submitAssessment()
{
    $http = new Http();
    $url = "https://corednacom.corewebdna.com/assessment-endpoint.php";
    $tokenResponse = $http->options($url);

    $response = $http->post(
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
