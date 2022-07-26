<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class StaticEnumStaticMethodCall
{
    public function run($value)
    {
        $compare = SomeEnum::from($value);
        $compare = SomeEnum::values();
        $compare = SomeEnum::keys();
        $compare = SomeEnum::isValid($value);
        $compare = SomeEnum::search($value);
        $compare = SomeEnum::toArray();
        SomeEnum::assertValidValue($value);
    }
}
