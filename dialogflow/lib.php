<?php

/**
 * Return a value from an array with specified path.
 *
 * @param array $array
 * @param string $path
 * @param mixed|null $default
 * @param string $separator
 * @return mixed|null
 */

/**
 * "Normalize" JSON by inserting null values
 *
 * Google seems to build JSON structures like `{"parameters":}`, which causes the php built-in JSON parser to to fail
 * with an Syntax Error and return null. But we want to deliver valid JSON.
 *
 * @param string $input
 * @return string
 */
function allioc_normalizeJSON($input) {
    return preg_replace('/("[^"]*":)([,}])/', '$1null$2', $input);
}

function allioc_prettifyJSON($input) {
    $inputStr = json_decode($input,true,getenv('FULFILLMENT_MESSAGE_MAX_NESTING') + 2);
    return json_encode($inputStr,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

/**
 * End execution with http error.
 *
 * @param string|null $message
 * @param int $responseCode
 */
function allioc_error($message = null, $responseCode = 500) {
    http_response_code($responseCode);
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(['statusCode' => $responseCode, 'status' => $message ?: 'Error']));
}
