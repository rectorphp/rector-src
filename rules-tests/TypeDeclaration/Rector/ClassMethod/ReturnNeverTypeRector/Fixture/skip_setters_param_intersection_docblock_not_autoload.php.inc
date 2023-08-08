<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

final class SkipSettersParamIntersectionDocblockNotAutoload
{
    /**
     * @param Type&Parts[] $value
     */
    public function setter($value): void
    {
        $this->prop = $value;
    }
}
