<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalFloat
{
    public function equal()
    {
        if ($this->getValue() == 1.0) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * @return float<0, max>
     */
    private function getValue(): float
    {
        return 1.0;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalFloat
{
    public function equal()
    {
        if ($this->getValue() === 1.0) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * @return float<0, max>
     */
    private function getValue(): float
    {
        return 1.0;
    }
}

?>
