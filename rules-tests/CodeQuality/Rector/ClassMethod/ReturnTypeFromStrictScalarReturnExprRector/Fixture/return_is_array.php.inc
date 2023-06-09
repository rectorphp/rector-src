<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnIsArray
{
    public function run($value)
    {
        return is_array(ftp_nlist($value, '.'));
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnIsArray
{
    public function run($value): bool
    {
        return is_array(ftp_nlist($value, '.'));
    }
}

?>
