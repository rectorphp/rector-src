<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInYield
{
    public function getSubscribedEvents(string $nodePath): iterable
    {
        yield $this->textElement(...);
    }

    public function textElement() { /* ... */ }
}
