<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\InfiniteIfElse\Fixture;

class Fixture
{
    public static function getMethod(): int {
        switch (mt_rand()) {
            case 1:
                return 10;
            case 2:
                return 20;
        }
        return 30;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\InfiniteIfElse\Fixture;

class Fixture
{
    public static function getMethod(): int {
        if (mt_rand() == 1) {
            return 10;
        } elseif (mt_rand() == 2) {
            return 20;
        }
        return 30;
    }
}

?>
