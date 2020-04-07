<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Functions\Files\Exceptions\Directory;

use pointybeard\Helpers\Exceptions\ReadableTrace;

class AlreadyExistsException extends ReadableTrace\ReadableTraceException
{
    public function __construct(string $name, int $code = 0, \Exception $previous = null)
    {
        parent::__construct("Unable to realise '{$name}'. Directory already exists.", $code, $previous);
    }
}
