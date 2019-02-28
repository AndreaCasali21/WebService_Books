<?php

// create the header
header ("Content-Type_application/json");

// include the token and the parser in this exact order
include ('token.php');
include ('token_impl.php');
include ('interpreter.php');

// loaded database data
$DB_DATA = array();

/**
 * Retrivies the content of the file provided by the path
 */
function load_and_decode(string $path) {
    return ( json_decode(file_get_contents($path), true) );
}

/**
 * Loads all the json archives and stores the data on the $DB_DATA array
 */
function load_db() {
    return true;
}

/**
 * Handles the http response to a client
 */
function http_response(int $code, string $codemsg, string $data) {
    header("HTTP/1.1 $code $codemsg");

    $response = array();
    $response['status'] = $code;
    $response['status_message'] = $codemsg;
    $response['data'] = $data;

    echo json_encode($response);
}

// check wich request is incoming
if (empty($_GET['query'])) {
    http_response(400, 'no command', 'unable to handle the request');
    die();
}

// load the database files
load_db();

// execute the query
$res = parse_query($_GET['query']);

// check whether the query is failed
if ($res === false) {
    http_response(400, 'error', $ERROR_MSG);
    die();
}

// return the response
http_response(200, 'response', $res);

?>
