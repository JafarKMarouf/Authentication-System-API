<?php

namespace App\Exceptions;

use Exception;

class CustomeException extends Exception
{
    protected $custom_code;

    public function __construct($message, $customCode)
    {
        parent::__construct($message, $customCode);

        $this->custom_code = $customCode;
    }

    public function getCustomCode()
    {
        return $this->custom_code;
    }
}
