<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

class PossibleVoid2
{
    public function __toString()
    {
        if (rand(0, 1)) {
            return;
        }
        return 'something';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

class PossibleVoid2 implements \Stringable
{
    public function __toString(): string
    {
        if (rand(0, 1)) {
            return '';
        }
        return 'something';
    }
}

?>
