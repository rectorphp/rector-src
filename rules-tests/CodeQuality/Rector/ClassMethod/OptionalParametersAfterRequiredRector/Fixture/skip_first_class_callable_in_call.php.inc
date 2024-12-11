<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInCall
{
    public function getSubscribedEvents()
    {
        return $this->textElement(...)->execute(...);
    }

    public function textElement()
    {
    }
}
