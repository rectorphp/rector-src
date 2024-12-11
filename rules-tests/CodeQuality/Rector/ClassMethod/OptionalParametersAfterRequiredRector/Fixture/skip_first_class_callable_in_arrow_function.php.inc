<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInArrowFunction
{
    public function getSubscribedEvents(string $nodePath): iterable
    {
        fn() => $this->textElement(...);
    }

    public function textElement() { /* ... */ }
}
