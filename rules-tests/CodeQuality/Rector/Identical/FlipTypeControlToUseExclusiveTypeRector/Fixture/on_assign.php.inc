<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

class OnAssign
{
    public function run(?\DateTimeImmutable $param)
    {
        if (null === ($paramValue = $param)) {
            return 'no';
        }

        return $paramValue;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

class OnAssign
{
    public function run(?\DateTimeImmutable $param)
    {
        if (!($paramValue = $param) instanceof \DateTimeImmutable) {
            return 'no';
        }

        return $paramValue;
    }
}

?>
