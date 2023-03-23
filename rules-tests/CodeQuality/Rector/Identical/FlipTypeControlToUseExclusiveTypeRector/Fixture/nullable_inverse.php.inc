<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;

final class NullableInverse
{
    public function run(?PhpDocInfo $phpDocInfo)
    {
        if ($phpDocInfo !== null) {
            return $phpDocInfo->getNode();
        }

        return null;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;

final class NullableInverse
{
    public function run(?PhpDocInfo $phpDocInfo)
    {
        if ($phpDocInfo instanceof \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo) {
            return $phpDocInfo->getNode();
        }

        return null;
    }
}

?>
