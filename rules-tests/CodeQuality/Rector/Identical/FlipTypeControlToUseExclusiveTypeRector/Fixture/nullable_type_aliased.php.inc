<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

use PhpParser\Node\Scalar\Int_ as SomeInt;

class NullableTypeAliased
{
    public function run()
    {
        $intNode = $this->getIntNode();
        if ($intNode === null) {
            return;
        }
    }

    private function getIntNode(): ?SomeInt
    {
        if (rand(0, 1)) {
            return new SomeInt(1);
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

use PhpParser\Node\Scalar\Int_ as SomeInt;

class NullableTypeAliased
{
    public function run()
    {
        $intNode = $this->getIntNode();
        if (!$intNode instanceof \PhpParser\Node\Scalar\Int_) {
            return;
        }
    }

    private function getIntNode(): ?SomeInt
    {
        if (rand(0, 1)) {
            return new SomeInt(1);
        }

        return null;
    }
}

?>
