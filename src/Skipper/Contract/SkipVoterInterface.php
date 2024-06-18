<?php

declare(strict_types=1);

namespace Rector\Skipper\Contract;

/**
 * @api implement with custom extension if needed
 */
interface SkipVoterInterface
{
    public function match(string | object $element): bool;

    public function shouldSkip(string | object $element, string $filePath): bool;
}
