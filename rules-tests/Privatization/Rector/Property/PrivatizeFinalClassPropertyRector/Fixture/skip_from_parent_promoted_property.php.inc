<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

use Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Source\AbstractClassWithProtectedProperty;

final class SkipFromParentPromotedProperty extends AbstractClassWithProtectedProperty
{
    public function __construct(protected $value)
    {
    }
}

?>
