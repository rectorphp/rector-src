<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipKnownType
{
    public array $bar;

    public function setBar(?array $bar): void
    {
        if ($bar === null) {
            $bar = [];
        }

        $this->bar = $bar;
    }
}
