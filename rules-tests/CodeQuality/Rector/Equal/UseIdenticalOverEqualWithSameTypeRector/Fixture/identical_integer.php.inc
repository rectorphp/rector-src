<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalInteger
{
    public function equal()
    {
        if ($this->getValue() == 1) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * @return int<0, max>
     */
    private function getValue(): int
    {
        return 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalInteger
{
    public function equal()
    {
        if ($this->getValue() === 1) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * @return int<0, max>
     */
    private function getValue(): int
    {
        return 1;
    }
}

?>
