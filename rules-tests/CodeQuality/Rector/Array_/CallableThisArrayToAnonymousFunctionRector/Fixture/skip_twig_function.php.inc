<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

use Manual\Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

final class SkipTwigFunction extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('runLocal', [$this, 'runLocal']),
        ];
    }

    public function runLocal(): int
    {
        return 1000;
    }
}
