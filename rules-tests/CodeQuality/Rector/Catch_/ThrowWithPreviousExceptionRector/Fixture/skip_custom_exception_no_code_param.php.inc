<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

use stdClass;

class Error extends \Exception
{
    public function __construct(
        string $message = '',
        $nodes = null,
        ?stdClass $source = null,
        ?array $positions = null,
        ?array $path = null,
        ?\Throwable $previous = null,
        ?array $extensions = null,
        ?array $unaliasedPath = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}

try {
    throw new \InvalidArgumentException('foo');
} catch (\InvalidArgumentException $e) {
    throw new Error(message: 'Some error message', previous: $e);
}