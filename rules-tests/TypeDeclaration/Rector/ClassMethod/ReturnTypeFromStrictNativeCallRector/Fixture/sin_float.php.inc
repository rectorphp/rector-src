<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class SinFloat
{
    public function run(float $input)
    {
        return sin($input);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class SinFloat
{
    public function run(float $input): float
    {
        return sin($input);
    }
}

?>
