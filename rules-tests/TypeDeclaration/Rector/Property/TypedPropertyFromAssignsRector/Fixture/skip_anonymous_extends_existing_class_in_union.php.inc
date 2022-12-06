<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class SkipAnonymousExtendsExistingClassInUnion
{
    private $x;

    public function __construct()
    {
        if (rand(0,1)) {
            $this->x = new \DateTime('now');
        } else {
            $this->x = new class extends \DateTime {};
        }
    }
}
