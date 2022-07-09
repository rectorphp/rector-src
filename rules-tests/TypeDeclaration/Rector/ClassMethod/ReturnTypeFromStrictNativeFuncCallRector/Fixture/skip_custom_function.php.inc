<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeFuncCallRector\Fixture;

function custom_function(): string
{
    return 'yes';
}

final class SkipCustomFunction
{
    public function run()
    {
        return custom_function();
    }
}
