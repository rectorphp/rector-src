<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInCallFunction
{
    public function getSubscribedEvents()
    {
        return textElement(...)->execute(...);
    }

    public function textElement()
    {
    }
}
