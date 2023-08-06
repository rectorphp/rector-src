<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ConditionalTypeForParameter
{
    public function run()
    {
        $username = 'xxxxxx';
        $password = 'yyyyyyy';
        return base64_encode(implode(':', [$username, $password]));
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ConditionalTypeForParameter
{
    public function run(): string
    {
        $username = 'xxxxxx';
        $password = 'yyyyyyy';
        return base64_encode(implode(':', [$username, $password]));
    }
}

?>
