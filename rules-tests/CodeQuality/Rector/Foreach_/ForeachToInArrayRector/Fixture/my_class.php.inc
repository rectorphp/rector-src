<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

final class MyClass
{
    public function foreachToInArray(array $items): bool
    {
        foreach ($items as $item) {
            if ($item == 'something') {
                return true;
            }
        }

        return false;
    }

    public function foreachToInArrayYoda(array $items): bool
    {
        foreach ($items as $item) {
            if ('something' == $item) {
                return true;
            }
        }

        return false;
    }

    public function foreachToInArrayStrict(array $items): bool
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return true;
            }
        }

        return false;
    }

    public function invertedForeachToInArrayStrict(array $items): bool
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return false;
            }
        }

        return true;
    }

    public function foreachToInArrayWithToVariables(array $items): bool
    {
        foreach ($items as $item) {
            if ($something === $item) {
                return true;
            }
        }

        return false;
    }

    public function foreachWithoutReturnFalse(array $items)
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return true;
            }
        }
    }

    public function foreachReturnString(array $items)
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return true;
            }
        }

        return 'false';
    }

    public function foreachWithSomethingElseAfterIt(array $items)
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return true;
            }
        }

        $foo = 'bar';
    }

    public function foreachWithElseNullable(array $items)
    {
        foreach ($items as $item) {
            if ('string') {
                return true;
            }
        }

        return;
    }

    public function alwaysTrue(array $items): bool
    {
        foreach ($items as $item) {
            if ($something === $item) {
                return true;
            }
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

final class MyClass
{
    public function foreachToInArray(array $items): bool
    {
        return in_array('something', $items);
    }

    public function foreachToInArrayYoda(array $items): bool
    {
        return in_array('something', $items);
    }

    public function foreachToInArrayStrict(array $items): bool
    {
        return in_array('something', $items, true);
    }

    public function invertedForeachToInArrayStrict(array $items): bool
    {
        return !in_array('something', $items, true);
    }

    public function foreachToInArrayWithToVariables(array $items): bool
    {
        return in_array($something, $items, true);
    }

    public function foreachWithoutReturnFalse(array $items)
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return true;
            }
        }
    }

    public function foreachReturnString(array $items)
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return true;
            }
        }

        return 'false';
    }

    public function foreachWithSomethingElseAfterIt(array $items)
    {
        foreach ($items as $item) {
            if ($item === 'something') {
                return true;
            }
        }

        $foo = 'bar';
    }

    public function foreachWithElseNullable(array $items)
    {
        foreach ($items as $item) {
            if ('string') {
                return true;
            }
        }

        return;
    }

    public function alwaysTrue(array $items): bool
    {
        foreach ($items as $item) {
            if ($something === $item) {
                return true;
            }
        }

        return true;
    }
}

?>
