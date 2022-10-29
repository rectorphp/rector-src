<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

function bcadd2($a, $b): string
{
    return "test";
}

class DoNotCrashOnNumericString
{
    public function run(): string
    {
        return bcadd2('10', '5');
    }
}