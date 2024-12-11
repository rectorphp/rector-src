<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInYield2
{
    public function getSubscribedEvents(string $nodePath): iterable
    {
        yield from $this->textElement(...);
    }

    public function textElement() { return []; }
}
