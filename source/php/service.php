<?php

// loaded database data
$DB_DATA = array();

// the tokens types
const TYPE_CMD = 0;
const TYPE_LOP = 1;
const TYPE_CMP = 2;

// the index of the command on the query
const CMD_IDX = 0;

// the expressions types
const EXPR_VAL = 0;
const EXPR_PROP = 1;

// the list of tokens avaible for this language with
// the list of avaible commands supported by the language
$TOKENS = array(
    // counts the evaluated query
    'COUNT' => {
        // the action to perform with this command
        'action' => function ($param) {

        },
        // the type of the token
        'type' => TYPE_CMD,
        // the number of params necessary
        'params' => 1
    },
    // connects two expessions
    'AND' => {
        // the action to perform with this command
        'action' => function ($first, $second) {

        }
        // the type of the token
        // LOP stands for LOGIC OPERATOR
        'type' => TYPE_LOP,
        // the number of params necessary
        'params' => 2
    },
    // connects two expessions
    'OR' => {
        // the action to perform with this command
        'action' => function ($first, $second) {

        }
        // the type of the token
        // LOP stands for LOGIC OPERATOR
        'type' => TYPE_LOP,
        // the number of params necessary
        'params' => 2
    },
    // compares two expessions
    'IS' => {
        // the action to perform with this command
        'action' => function ($first, $second) {

        }
        // the type of the token
        'type' => TYPE_CMP,
        // the number of params necessary
        'params' => 2
    },
    // compares two expessions
    'NOT_IS' => {
        // the action to perform with this command
        'action' => function ($first, $second) {

        }
        // the type of the token
        'type' => TYPE_CMP,
        // the number of params necessary
        'params' => 2
    }
);

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
}

/**
 * Parses the provided query as MRQL (MeetiX Reduced Query Language)
 */
function parse_query(string $query) {
    // all the tokens are putted on this array
    $tokens = array();

    // so now split the string in a list of tokens
    // that are subsequently interpreted by a very
    // basic check alghorithm
    $token = strtok($query, ' ');
    while ($token !== false) {
        array_push($tokens, $token);
        $token = strtok($query);
    }

    // now each token must be trimmed
    foreach ($tokens as &$token)
        $token = trim($token);

    // now parse each tooken to get the right action to do
    // the first token must be the command, so search it
    // into the $COMMANDS array of functions
    if (!array_key_exists($token[CMD_IDX], $TOKENS) || $TOKENS[$token[CMD_IDX]]['type'] != TYPE_CMD) {
        $ERROR_MSG = "query command not supported";
        return false;
    }

    // now put on the array actions the actions
    // to perform with the query provided, NOTE
    // for the commands the param in not merged
    // with the token descriptor because this is
    // not necessary
    $expressions = array();
    $actions = array($TOKENS[$token[CMD_IDX]]);

    // the following tokens must be tokens, parameters,
    // expessions and so on, so search them on the TOKENS
    // array and get the actions
    foreach (next($tokens) as $token) {
        // if the token does not exists on the tokens array
        // we take it as expession, commonly <table.property> or <value>
        if (!array_key_exists($token, $TOKENS)) {
            // check whether the expression is a <table.property> or <value>
            $type = EXPR_PROP;
            if (strpos($token, '.') !== false) $type = EXPR_VAL;

            // put the value on the array
            array_push($expressions, array('string' => $token, 'type' => $type));
        }

        // collect all the parameters, NOTE this is a
        // very orrible solutions, but for now works
        $tkparams = array();
        $action = $TOKENS[$token];
        if ($action['params'] == 1) {
            array_push($tkparams, &next($tokens));
            prev($tokens);
        }
        else if ($action['params'] == 2) {
            array_push($tkparams, &prev($tokens));
            next($tokens);
            array_push($tkparams, &next($tokens));
            prev($tokens);
        }

        // put on the actions array the provided actions
        array_push($actions, array_merge($action, array('params' => $tkparams));
    }

    // so now is time to evaluate and execute properly the query
    // so first of all check all the expessions checking whether
    // are requested valid table requesting
    foreach ($expressions as &$expression) {
        // check whether the expression is a property request
        if ($expression['type'] == EXPR_PROP) {
            // explode all the expression, that commonly
            // is expected is a <table.property>
            $exprstr = $expression['string'];
            $parts = explode('.', $exprstr);

            // check whether the table exists
            if (!count($parts) || count($parts) < 2) {
                $ERROR_MSG = "supplied an invalid table reference";
                return false;
            }

            // check whether the table exists
            if (!array_key_exists($parts[0], $DB_DATA)) {
                $ERROR_MSG = "requested an unexisting table on the DB";
                return false;
            }

            // check whether the property exists on the table
            if (!array_key_exists($parts[1], $DB_DATA[$parts[0]][0])) {
                $ERROR_MSG = "requested an unexisting table property";
                return false;
            }

            // so we can load from the db the values requested
            $values = array();
            foreach ($DB_DATA[$parts[0]] as $tuple)
                array_push($values, $tuple[$parts[1]]);

            // now put on the expression the values loaded
            $expression['values'] = $values;
        }
    }

    // now start to perform the query
    foreach (array_reverse($actions) as $action)
        $action['action']($action['params']);
}

// create the header
header ("Content-Type_application/json");

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
