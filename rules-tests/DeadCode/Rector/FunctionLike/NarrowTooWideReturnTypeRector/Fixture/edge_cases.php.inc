<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class EdgeCases
{
    public function implicitNull(): string|int|null
    {
        if (rand(0, 1)) {
            return;
        }

        return 'something';
    }

    public function implicitReturn(): string|int|null
    {
        if (rand(0, 1)) {
            return 'something';
        }
    }

    public function noReturn(): string|int|null
    {
        if (rand(0, 1)) {
            echo 'something';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class EdgeCases
{
    public function implicitNull(): ?string
    {
        if (rand(0, 1)) {
            return;
        }

        return 'something';
    }

    public function implicitReturn(): ?string
    {
        if (rand(0, 1)) {
            return 'something';
        }
    }

    public function noReturn(): void
    {
        if (rand(0, 1)) {
            echo 'something';
        }
    }
}

?>
