<?php
declare(strict_types=1);


namespace Rector\Tests\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector\Source;


final class SomeOtherClass
{
    public string $property = '';

    public string $property2 = '';

    public function method(): void
    {

    }
}
