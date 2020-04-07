<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Functions\Files\Exceptions\Directory;

use pointybeard\Helpers\Exceptions\ReadableTrace;

class CreationFailedException extends ReadableTrace\ReadableTraceException
{
    public function __construct(string $name, string $message, int $code = 0, \Exception $previous = null)
    {
        parent::__construct("Unable to realise '{$name}'. Returned: {$message}", $code, $previous);
    }
}
