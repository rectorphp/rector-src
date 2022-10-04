<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipNotEqualAssignmentConstructorPromotion
{
    public function __construct(private string $value)
    {
		    $this->value = trim($value);
    }
}
