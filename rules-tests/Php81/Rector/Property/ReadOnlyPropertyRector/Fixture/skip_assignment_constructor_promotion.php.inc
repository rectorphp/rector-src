<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipAssignmentConstructorPromotion
{
    public function __construct(private string $value)
    {
		    $this->value = 'any value';
    }
}
