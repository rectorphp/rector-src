<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator\ValueObject;

final class Commit
{
    public function __construct(
        private readonly string $hash,
        private readonly string $message
    ) {
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
