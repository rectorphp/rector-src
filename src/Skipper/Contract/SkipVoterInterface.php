<?php

declare(strict_types=1);

namespace Rector\Skipper\Contract;

interface SkipVoterInterface
{
    public function match(string | object $element): bool;

    public function shouldSkip(string | object $element, string $filePath): bool;
}
