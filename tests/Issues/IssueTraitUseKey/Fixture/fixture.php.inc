<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueTraitUseKey\Fixture;

use Rector\Core\Tests\Issues\IssueTraitUseKey\TraitA;
use Rector\Core\Tests\Issues\IssueTraitUseKey\TraitB;

class Fixture
{
    use TraitA, TraitB;

    public const PUBLIC_CONST = 2;

    private const PRIVATE_CONST = 1;

    protected $foo;

    public $bar;

    public function __construct(int $foo, int $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function getConst(): int
    {
        return self::PRIVATE_CONST ?? self::PUBLIC_CONST;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueTraitUseKey\Fixture;

use Rector\Core\Tests\Issues\IssueTraitUseKey\TraitA;
use Rector\Core\Tests\Issues\IssueTraitUseKey\TraitB;

final class Fixture
{
    use TraitA;
    use TraitB;

    public const PUBLIC_CONST = 2;

    private const PRIVATE_CONST = 1;

    public function __construct(protected int $foo, public int $bar)
    {
    }

    public function getConst(): int
    {
        return self::PRIVATE_CONST ?? self::PUBLIC_CONST;
    }
}

?>
