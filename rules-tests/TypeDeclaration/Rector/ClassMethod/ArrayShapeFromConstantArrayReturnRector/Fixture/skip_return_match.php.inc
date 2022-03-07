<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class SkipReturnMatch
{
    public function run($item)
    {
        return match ($item) {
            '100' => 100,
            '200' => 200,
        };
    }
}
