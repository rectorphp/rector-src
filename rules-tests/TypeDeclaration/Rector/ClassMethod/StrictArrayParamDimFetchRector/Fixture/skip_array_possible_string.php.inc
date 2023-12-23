<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipArrayPossibleString
{
    public function resolve($number)
    {
        $check = 0;

        for ($i = 0; $i < strlen($number); $i++) {
            $check = $number[$i];
        }

        return $check;
    }
}
