<?php

// loaded database data
$DB_DATA = array();

// the tokens types
const TYPE_CMD = 0;
const TYPE_LOP = 1;
const TYPE_CMP = 2;

// the index of the command on the query
const CMD_IDX = 0;
const TABLE_IDX = 0;
const PROP_IDX = 1;

// the expressions types
const EXPR_VAL = 0;
const EXPR_PROP = 1;

// token and syntax identifiers
const TOKEN_WORK_SEPRT  = ' ';
const TOKEN_PROP_ACCESS = '.';
const TOKEN_COUNT = 'COUNT';
const TOKEN_AND   = 'AND';
const TOKEN_OR    = 'OR';
const TOKEN_IS    = 'IS';
const TOKEN_NIS   = 'NOT_IS';

/**
 * The token rappresentation for the language internally
 * contains the type of the token, the action to perform
 * and the parameters for the action itself
 */
class Token {
    /**
     * the type of the token contained
     * @var int
     */
    private $type;

    /**
     * the action that must be executed for this token
     * @var Callable
     */
    private $action;

    /**
     * Filled constructor
     *
     * @param int type:             the type of tìoken
     * @param Callable action:      the action to call for this token
     */
    public function __construct($type, $action) {
        $this->type = $type;
        $this->action = $action;
    }

    /**
     * @return int type of the token
     */
    function getType() {
        return ( $this->type );
    }

    /**
     * @return Callable action of this token
     */
    function getAction() {
        return ( $this->action );
    }

    /**
     * Calls the action providing to him the parameters
     * and returning the processing value, this can be called only once
     *
     * @return ? the function return value
     */
    function call($params) {
        return ( $this->action($params) );
    }
}

// the list of tokens avaible for this language with
// the list of avaible commands supported by the language
$TOKENS = array(
    // counts the evaluated query
    TOKEN_COUNT => new Token(
        // the count token is a command, this means that
        // must be the first token on the query string
        TYPE_CMD,
        // the action to perform for this token
        function ($params) {

        }
    ),
    // connects two expessions
    TOKEN_AND => new Token(
        // the AND token is a logic operator
        TYPE_LOP,
        // the action to perform for this token
        function ($params) {

        }
    ),
    // connects two expessions
    TOKEN_OR => new Token(
        // the OR token is a logic operator
        TYPE_LOP,
        // the action to perform for this token
        function ($params) {

        }
    ),
    // compares two expessions
    TOKEN_IS => {
        // the action to perform with this command
        'action' => function ($params) {

        }
        // the type of the token
        'type' => TYPE_CMP,
        // the number of params necessary
        'params' => 2
    },
    // compares two expessions
    TOKEN_NIS => {
        // the action to perform with this command
        'action' => function ($params) {
            // split the two paramters
            $exprleft = $params[0];
            $exprright = $params[1];

            // now we have the tables

        }
        // the type of the token
        'type' => TYPE_CMP,
        // the number of params necessary
        'params' => 2
    }
);

/**
 * Checks whether the <$str> string contains the <$needle> one
 */
function str_contains(string $str, string $needle) {
    return ( strpos($str, $needle) !== false );
}

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
    // check whether the separator token and the table access token are valid
    if (TOKEN_WORK_SEPRT != ' ' || TOKEN_WORK_SEPRT == TOKEN_PROP_ACCESS) {
        $ERROR_MSG = 'bad configured token for separator and table access';
        return false;
    }

    // all the tokens are putted on this array
    $tokens = array();

    // so now split the string in a list of tokens
    // that are subsequently interpreted by a very
    // basic check alghorithm, each token before the
    // insertion on the <tokens> array is trimmed of
    // any useless characters
    {
        $token = strtok($query, ' ');
        while ($token !== false) {
            array_push($tokens, trim($token));
            $token = strtok($query);
        }
    }

    // now parse each tooken to get the right action to do
    // the first token must be the command, so search it
    // into the $COMMANDS array of functions
    if (!array_key_exists($tokens[CMD_IDX], $TOKENS) || $TOKENS[$tokens[CMD_IDX]]['type'] != TYPE_CMD) {
        $ERROR_MSG = "query command not supported or unexisting";
        return false;
    }

    // now put on the array actions the actions
    // to perform with the query provided, NOTE
    // for the commands the param in not merged
    // with the token descriptor because this is
    // not necessary
    $expressions = array();
    $actions = array($TOKENS[$tokens[CMD_IDX]]);

    // the following tokens must be tokens, parameters,
    // expessions and so on, so search them on the TOKENS
    // array and get the actions
    foreach (next($tokens) as $token) {
        // if the token does not exists on the tokens array
        // we take it as expession, commonly the parser expected
        // <table<TOKEN_PROP_ACCESS>property> or <value>
        if (!array_key_exists($token, $TOKENS)) {
            // check whether the expression is a <table.property> or
            // <value>,  for now the discriminant beetween the two
            // expressions types is the <TOKEN_PROP_ACCESS> character(s)
            $type = EXPR_PROP;
            if (str_contains($token, TOKEN_PROP_ACCESS))
                $type = EXPR_VAL;

            // put the value on the array
            array_push($expressions, array(
                'expr' => $token,
                'type' => $type
            ));
        }

        // collect all the parameters, NOTE this is a
        // very orrible solutions, but for now works
        $params = array();
        $action = $TOKENS[$token];
        switch ($action['params']) {
            case 1:
                array_push($params, &next($tokens));
                prev($tokens);
                break;
            case 2:
                array_push($params, &prev($tokens));
                next($tokens);
                array_push($params, &next($tokens));
                prev($tokens);
                break;
        }

        // put on the actions array the provided actions
        $action['params'] = $params;
        array_push($actions, $action);
    }

    // so now is time to evaluate and execute properly the query
    // so first of all check all the expessions checking whether
    // are requested valid table requesting
    foreach ($expressions as &$expression) {
        // check whether the expression is a property request
        if ($expression['type'] == EXPR_PROP) {
            // explode all the expression, that commonly
            // is expected is a <table.property>
            $exprstr = $expression['expr'];
            $parts = explode(TOKEN_PROP_ACCESS, $exprstr);

            // check whether the table exists
            if (!count($parts) || count($parts) < 2) {
                $ERROR_MSG = "supplied an invalid table reference";
                return false;
            }

            // check whether the table exists
            if (!array_key_exists($parts[TABLE_IDX], $DB_DATA)) {
                $ERROR_MSG = "requested an unexisting table on the DB";
                return false;
            }

            // check whether the property exists on the table
            if (!array_key_exists($parts[PROP_IDX], $DB_DATA[$parts[TABLE_IDX]][0])) {
                $ERROR_MSG = "requested an unexisting table property";
                return false;
            }

            // so we can load from the db the values requested
            $values = array();
            foreach ($DB_DATA[$parts[TABLE_IDX]] as $tuple) {
                // put on the values array the entire tuple that is needed
                // later for eventual selection, and the value to check
                array_push($values, array(
                    'tuple' => $tuple,
                    'value' => $tuple[$parts[PROP_IDX]];
                ));
            }

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
