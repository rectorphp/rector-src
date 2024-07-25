<?php

declare(strict_types=1);

namespace Rector\PhpParser\Parser;

use PHPStan\Parser\ParserErrorsException;

final class ParserErrors
{
    private string $message;

    private int $line;

    public function __construct(ParserErrorsException $exception)
    {
        $this->message = $exception->getMessage();
        $this->line = $exception->getAttributes()['startLine'] ?? 0;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLine(): int
    {
        return $this->line;
    }
}
