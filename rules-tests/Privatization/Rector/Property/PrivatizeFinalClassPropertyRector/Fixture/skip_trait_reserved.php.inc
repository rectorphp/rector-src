<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

use Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Source\SomeTraitWithProtectedProperty;

final class SkipTraitReserved
{
    use SomeTraitWithProtectedProperty;

    protected $value = 100;
}
