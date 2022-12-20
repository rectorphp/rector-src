<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Source\SomeExactType;

final class EmptyOnExactType
{
    public function run()
    {
        $exactType = $this->getExactType();
        if (empty($exactType)) {
            return;
        }
    }

    private function getExactType(): ?SomeExactType
    {
        if (mt_rand(0, 1)) {
            return new SomeExactType();
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Source\SomeExactType;

final class EmptyOnExactType
{
    public function run()
    {
        $exactType = $this->getExactType();
        if (!$exactType instanceof \Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Source\SomeExactType) {
            return;
        }
    }

    private function getExactType(): ?SomeExactType
    {
        if (mt_rand(0, 1)) {
            return new SomeExactType();
        }

        return null;
    }
}

?>
