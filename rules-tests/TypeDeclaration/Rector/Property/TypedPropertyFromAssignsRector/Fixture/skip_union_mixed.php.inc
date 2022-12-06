<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class SkipUnionMixed
{
    private $x;

    public function __construct($x)
    {
        if (rand(0,1)) {
            $this->x = $x;
        } else {
            $this->x = null;
        }
    }
}