<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class IntOrNumericString
{
    private function getAssetSize(): int
    {
        return 1000;
    }

    public function getTotalFilesize($assets)
    {
        if (empty($assets)) {
            return 0;
        }

        $size = $this->getAssetSize($assets);

        if ($size) {
            $size = (string) $size;
        }

        return $size;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class IntOrNumericString
{
    private function getAssetSize(): int
    {
        return 1000;
    }

    public function getTotalFilesize($assets): int|string
    {
        if (empty($assets)) {
            return 0;
        }

        $size = $this->getAssetSize($assets);

        if ($size) {
            $size = (string) $size;
        }

        return $size;
    }
}

?>
