<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

final class SkipParentName extends \MyCLabs\Enum\Enum
{
    public function run($value)
    {
        $compare = parent::someCall();
    }
}
