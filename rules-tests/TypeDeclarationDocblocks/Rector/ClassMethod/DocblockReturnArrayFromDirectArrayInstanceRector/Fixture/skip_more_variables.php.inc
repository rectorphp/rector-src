<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SkipMoreVariables
{
    public function getNames()
    {
        $variable = [
            'key' => 'value',
        ];


        return $variable;
    }
}
