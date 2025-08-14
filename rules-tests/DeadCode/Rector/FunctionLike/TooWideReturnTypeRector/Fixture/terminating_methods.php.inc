<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

final class TerminatingMethods
{
    public function alwaysReturns(): string|int|null
    {
        if (rand(0, 1)) {
            return 'text';
        } else {
            return 42;
        }
    }

    public function alwaysThrows(): string|int|null
    {
        if (rand(0, 1)) {
            throw new \Exception('error');
        } else {
            throw new \RuntimeException('runtime error');
        }
    }

    public function mixedTerminating(): string|int|null
    {
        if (rand(0, 1)) {
            return 'text';
        } elseif (rand(0, 1)) {
            throw new \Exception('error');
        } else {
            exit(1);
        }
    }

    public function switchTerminating(): string|int|null
    {
        switch (rand(0, 2)) {
            case 0:
                return 'zero';
            case 1:
                return 1;
            default:
                throw new \Exception('default');
        }
    }

    public function tryFinallyTerminating(): string|int|null
    {
        try {
            if (rand(0, 1)) {
                return 'success';
            }
            throw new \Exception('error');
        } finally {
            return 'finally';
        }
    }

    public function alwaysExits(): string|int|null
    {
        exit(1);
    }

    public function noReturnStatements(): string|int
    {
        echo 'something';
    }

    public function hasReturnButAlsoThrows(): string|int|null
    {
        if (rand(0, 1)) {
            return 'text';
        } else {
            throw new \Exception('error');
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

final class TerminatingMethods
{
    public function alwaysReturns(): string|int
    {
        if (rand(0, 1)) {
            return 'text';
        } else {
            return 42;
        }
    }

    public function alwaysThrows(): never
    {
        if (rand(0, 1)) {
            throw new \Exception('error');
        } else {
            throw new \RuntimeException('runtime error');
        }
    }

    public function mixedTerminating(): string
    {
        if (rand(0, 1)) {
            return 'text';
        } elseif (rand(0, 1)) {
            throw new \Exception('error');
        } else {
            exit(1);
        }
    }

    public function switchTerminating(): string|int
    {
        switch (rand(0, 2)) {
            case 0:
                return 'zero';
            case 1:
                return 1;
            default:
                throw new \Exception('default');
        }
    }

    public function tryFinallyTerminating(): string
    {
        try {
            if (rand(0, 1)) {
                return 'success';
            }
            throw new \Exception('error');
        } finally {
            return 'finally';
        }
    }

    public function alwaysExits(): never
    {
        exit(1);
    }

    public function noReturnStatements(): void
    {
        echo 'something';
    }

    public function hasReturnButAlsoThrows(): string
    {
        if (rand(0, 1)) {
            return 'text';
        } else {
            throw new \Exception('error');
        }
    }
}

?>
