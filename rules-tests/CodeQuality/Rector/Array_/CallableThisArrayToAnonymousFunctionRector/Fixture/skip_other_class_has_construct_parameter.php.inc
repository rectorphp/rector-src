<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

final class OtherClass
{
    private $property;

    public function __construct($property)
    {
        $this->property = $property;
    }

    public function someMethod()
    {
    }
}

final class SkipOtherClassHasConstructParameter
{
    public function run()
    {
        return [OtherClass::class, 'someMethod'];
    }
}
?>
