<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

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
