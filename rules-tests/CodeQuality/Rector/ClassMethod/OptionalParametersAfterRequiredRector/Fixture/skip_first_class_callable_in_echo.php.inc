<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipFirstClassCallableInEcho
{
    public function getSubscribedEvents()
    {
        echo $this->textElement(...);
    }

    public function textElement() { return 'test'; }
}
