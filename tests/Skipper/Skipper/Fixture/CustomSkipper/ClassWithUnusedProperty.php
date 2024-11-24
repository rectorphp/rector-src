<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper\Fixture\CustomSkipper;

#[SomeAttribute]
class ClassWithUnusedProperty extends SomeBaseClass {
    private string $unusedPropertyName;
}

class ClassWithUnusedProperty2 extends SomeBaseClass
{
    private string $unusedPropertyName;
}
