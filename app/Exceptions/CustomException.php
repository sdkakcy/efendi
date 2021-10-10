<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception {
    
    protected $type = 'error';

    public function __construct($message, $type = null, $code = 0)
    {
        if($type) {
            $this->type = $type;
        }

        parent::__construct($message, $code);
    }

    public function getType()
    {
        return $this->type;
    }
}