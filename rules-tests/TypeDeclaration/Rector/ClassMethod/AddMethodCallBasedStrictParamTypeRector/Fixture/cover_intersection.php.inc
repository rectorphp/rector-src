<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class CoverIntersection
{
    public function run(array $values): void
    {
        /** @var int[] $items */
        $items = [];

        foreach ($values as $value) {
            $items[] = 10;
        }

        $this->nextItems($items);
    }

    private function nextItems($items): void
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class CoverIntersection
{
    public function run(array $values): void
    {
        /** @var int[] $items */
        $items = [];

        foreach ($values as $value) {
            $items[] = 10;
        }

        $this->nextItems($items);
    }

    private function nextItems(array $items): void
    {
    }
}

?>
