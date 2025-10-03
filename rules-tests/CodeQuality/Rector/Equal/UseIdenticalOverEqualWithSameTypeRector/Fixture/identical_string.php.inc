<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalString
{
    public function equal()
    {
        if ($this->getValue() == 'hi') {
            return 'yes';
        }

        return 'no';
    }

    private function getValue(): string
    {
        return 'hello';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class IdenticalString
{
    public function equal()
    {
        if ($this->getValue() === 'hi') {
            return 'yes';
        }

        return 'no';
    }

    private function getValue(): string
    {
        return 'hello';
    }
}

?>
