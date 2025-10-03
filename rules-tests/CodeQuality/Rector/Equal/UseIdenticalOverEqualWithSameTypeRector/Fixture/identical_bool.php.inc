<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalBool
{
    public function equal()
    {
        if ($this->getValue() == true) {
            return 'yes';
        }

        return 'no';
    }

    private function getValue(): bool
    {
        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalBool
{
    public function equal()
    {
        if ($this->getValue() === true) {
            return 'yes';
        }

        return 'no';
    }

    private function getValue(): bool
    {
        return true;
    }
}

?>
