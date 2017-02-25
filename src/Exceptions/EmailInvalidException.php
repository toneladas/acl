<?php

namespace Toneladas\Exceptions;

class EmailInvalidException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Email is not valid");
    }
}
