<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class SkipMagicCalls
{
    public function run($value, $magicMethod)
    {
        $compare = SomeEnum::$magicMethod();
    }
}
