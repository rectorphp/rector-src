<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

function bcadd($a, $b): string
{
    return "test";
}

class DoNotCrashOnNumericString
{
    public function run(): string
    {
        return bcadd('10', '5');
    }
}
