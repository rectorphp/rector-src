<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\Suit;

class EnumReturns
{
    public function run(Suit $b)
    {
        if ($b === Suit::Hearts) {
            if (rand(0,1)) {

            }

            return $b;
        }

        if ($b === Suit::Diamonds || $b === Suit::Clubs) {
            return $b;
        }

        return $b;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\Suit;

class EnumReturns
{
    public function run(Suit $b)
    {
        if ($b === Suit::Hearts) {
            if (rand(0,1)) {

            }

            return Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\Suit::Hearts;
        }

        if ($b === Suit::Diamonds || $b === Suit::Clubs) {
            return $b;
        }

        return Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source\Suit::Spades;
    }
}

?>
