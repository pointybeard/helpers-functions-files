<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Functions\Files\Exceptions\Symlink;

use pointybeard\Helpers\Exceptions\ReadableTrace;

class DestinationExistsException extends ReadableTrace\ReadableTraceException
{
    public function __construct(string $name, int $code = 0, \Exception $previous = null)
    {
        parent::__construct("Symbolic link {$name} already exists.", $code, $previous);
    }
}
