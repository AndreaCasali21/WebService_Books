<?php


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
    function call(State $state) {
        return ( $this->action($params) );
    }
}

?>
