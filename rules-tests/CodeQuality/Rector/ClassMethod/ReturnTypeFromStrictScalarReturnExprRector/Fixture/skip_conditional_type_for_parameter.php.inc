<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class SkipConditionalTypeForParameter
{
    public function run()
    {
        $username = 'xxxxxx';
        $password = 'yyyyyyy';
        return base64_encode(implode(':', [$username, $password]));
    }
}
