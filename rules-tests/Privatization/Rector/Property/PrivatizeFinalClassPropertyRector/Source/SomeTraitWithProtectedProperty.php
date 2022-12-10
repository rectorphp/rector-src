<?php
declare(strict_types=1);

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Source;

trait SomeTraitWithProtectedProperty
{
    protected $value;
}
