<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class SkipTooDeepLevel
{
    public function getDefaultContext(): array
    {
        return [
             'foo' => [
                 'bar' => [
                    'baz' => 'bat',
                 ],
             ],
        ];
    }
}
