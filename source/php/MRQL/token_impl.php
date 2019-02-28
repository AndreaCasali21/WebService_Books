<?php

// the list of tokens avaible for this language with
// the list of avaible commands supported by the language
$TOKENS = array(
    // counts the evaluated query
    TOKEN_COUNT => new Token(
        // the count token is a command, this means that
        // must be the first token on the query string
        TYPE_CMD,
        // the action to perform for this token
        function (State $state) {

        }
    ),
    // connects two expessions
    TOKEN_AND => new Token(
        // the AND token is a logic operator
        TYPE_LOP,
        // the action to perform for this token
        function (State $state) {

        }
    ),
    // connects two expessions
    TOKEN_OR => new Token(
        // the OR token is a logic operator
        TYPE_LOP,
        // the action to perform for this token
        function (State $state) {

        }
    ),
    // compares two expessions
    TOKEN_IS => new Token(
        // the IS token is a comparer operator
        TYPE_CMP,
        // the action to perform with this command
        function (State $state) {

        }
    ),
    // compares two expessions
    TOKEN_NIS => new Token(
        // the IS token is a comparer operator
        TYPE_CMP,
        // the action to perform with this command
        function (State $state) {

        }
    )
);

?>
