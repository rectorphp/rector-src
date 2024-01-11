<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\Fixer;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SkipParentPublic as BaseSkipParentPublic;

final class SkipWhenNameIsDifferent
{
    private Fixer $fixer;

    public function __construct(Fixer $notSameName)
    {
        $this->fixer = $notSameName;
    }
}
