<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\BackedSuit;

class BackedEnumReturns
{
    public function run(BackedSuit $b)
    {
        if ($b === BackedSuit::Hearts) {
            if (rand(0,1)) {

            }

            return $b;
        }

        if ($b === BackedSuit::Diamonds || $b === BackedSuit::Clubs) {
            return $b;
        }

        return $b;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\BackedSuit;

class BackedEnumReturns
{
    public function run(BackedSuit $b)
    {
        if ($b === BackedSuit::Hearts) {
            if (rand(0,1)) {

            }

            return Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\BackedSuit::Hearts;
        }

        if ($b === BackedSuit::Diamonds || $b === BackedSuit::Clubs) {
            return $b;
        }

        return Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\BackedSuit::Spades;
    }
}

?>
