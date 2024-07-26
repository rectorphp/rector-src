<?php

declare(strict_types=1);

namespace Rector\PhpParser\Parser;

use PHPStan\Parser\ParserErrorsException;

final readonly class ParserErrors
{
    private string $message;

    private int $line;

    public function __construct(ParserErrorsException $parserErrorsException)
    {
        $this->message = $parserErrorsException->getMessage();
        $this->line = $parserErrorsException->getAttributes()['startLine'] ?? $parserErrorsException->getLine();
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
