<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInDo
{
    public function getSubscribedEvents()
    {
        do {

        } while ($this->textElement(...));
    }

    public function textElement()
    {
         return 1;
    }
}
