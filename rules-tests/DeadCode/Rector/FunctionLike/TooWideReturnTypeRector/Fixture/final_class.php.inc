<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

final class ClassMethodCase
{
    public function getData(): string|int|\DateTime
    {
        if (rand(0, 1)) {
            return 'text';
        }

        if (rand(0, 1)) {
            return 'text';
        }

        return 1000;
    }

    public function getInfo(): string|int|bool|null
    {
        if (rand(0, 1)) {
            return 'info';
        }

        if (rand(0, 1)) {
            return null;
        }

        return 42;
    }

    private function processData(): string|array|object
    {
        if (rand(0, 1)) {
            return 'processed';
        }

        return ['data' => 'value'];
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

final class ClassMethodCase
{
    public function getData(): string|int
    {
        if (rand(0, 1)) {
            return 'text';
        }

        if (rand(0, 1)) {
            return 'text';
        }

        return 1000;
    }

    public function getInfo(): string|int|null
    {
        if (rand(0, 1)) {
            return 'info';
        }

        if (rand(0, 1)) {
            return null;
        }

        return 42;
    }

    private function processData(): string|array
    {
        if (rand(0, 1)) {
            return 'processed';
        }

        return ['data' => 'value'];
    }
}

?>
