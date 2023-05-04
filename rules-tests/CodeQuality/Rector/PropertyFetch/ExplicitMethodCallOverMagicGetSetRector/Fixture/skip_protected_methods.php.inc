<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\PropertyFetch\ExplicitMethodCallOverMagicGetSetRector\Fixture;

use Rector\Tests\CodeQuality\Rector\PropertyFetch\ExplicitMethodCallOverMagicGetSetRector\Source\ObjectWithMagicCallsProtectedMethods;

final class SkipProtectedMethods
{
    public function run(ObjectWithMagicCallsProtectedMethods $object)
    {
        return $object->name;
    }
}
