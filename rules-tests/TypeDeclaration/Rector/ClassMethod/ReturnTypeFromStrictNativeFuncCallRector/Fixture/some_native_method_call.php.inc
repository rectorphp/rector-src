<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeFuncCallRector\Fixture;

final class SomeNativeMethodCall
{
    public function run(\SplFileInfo $splFileInfo)
    {
        return $splFileInfo->isDir();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeFuncCallRector\Fixture;

final class SomeNativeMethodCall
{
    public function run(\SplFileInfo $splFileInfo): bool
    {
        return $splFileInfo->isDir();
    }
}

?>
