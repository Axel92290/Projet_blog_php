<?php

namespace Tools;

use Exception;

/**
 * Class UserModelNotFound
 */
class UserModelNotFound extends Exception
{
    public function __construct($message = "User not found", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
?>