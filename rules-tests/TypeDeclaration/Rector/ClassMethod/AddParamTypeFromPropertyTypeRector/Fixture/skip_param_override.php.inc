<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipParamOverride
{
    /**
     * @var int[]
     */
    public array $bar;

    public function setBar($bar): void
    {
        if ($bar === null) {
            $bar = [];
        }

        $this->bar = $bar;
    }
}
