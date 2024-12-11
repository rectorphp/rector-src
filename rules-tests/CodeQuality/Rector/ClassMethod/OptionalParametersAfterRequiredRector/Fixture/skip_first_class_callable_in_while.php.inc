<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInWhile
{
    public function getSubscribedEvents()
    {
        while ($this->textElement(...)) {

        }
    }

    public function textElement()
    {
         return 1;
    }
}
