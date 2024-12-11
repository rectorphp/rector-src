<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInIf
{
    public function getSubscribedEvents()
    {
        if ($this->textElement(...)) {

        } elseif ($this->textElement(...)) {

        }
    }

    public function textElement()
    {
         return 1;
    }
}
