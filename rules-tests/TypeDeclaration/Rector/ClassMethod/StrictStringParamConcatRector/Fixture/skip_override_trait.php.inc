<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Source\SomeTrait;

final class SkipOverrideTrait
{
    use SomeTrait;

    public function run($var): void
    {
        $var .= 'test';
    }
}