<?php

namespace Core\Exceptions;

class HttpNotFoundException extends \Exception
{
    public function __construct(string $message = "Page non trouvée")
    {
        parent::__construct($message, 404);
    }
}
