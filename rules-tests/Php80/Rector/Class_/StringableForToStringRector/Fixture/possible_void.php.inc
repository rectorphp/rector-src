<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

class PossibleVoid
{
    public function __toString()
    {
        if (rand(0, 1)) {
            return 'something';
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

class PossibleVoid implements \Stringable
{
    public function __toString(): string
    {
        if (rand(0, 1)) {
            return 'something';
        }
        return '';
    }
}

?>
