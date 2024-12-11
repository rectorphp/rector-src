<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInUnary
{
    public function getSubscribedEvents(string $nodePath)
    {
        return -$this->textElement(...);
    }

    public function textElement() { return 1; }
}
