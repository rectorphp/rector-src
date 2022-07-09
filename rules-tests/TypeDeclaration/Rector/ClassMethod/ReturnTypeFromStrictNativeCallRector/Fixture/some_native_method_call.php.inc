<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

final class SomeNativeMethodCall
{
    public function run(\SplFileInfo $splFileInfo): bool
    {
        return $splFileInfo->isDir();
    }
}

?>
