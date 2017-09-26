<?php
namespace JPush\Exceptions;

class APIConnectionException extends \Exception {

    function __toString() {
        return "\n" . __CLASS__ . " -- {$message} \n";
    }
}
