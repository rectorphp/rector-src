<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

class WithFluentNoReturnNeverCalled
{
    public function foo()
    {
        return $this
            ->bar()
            ->bat();
    }

    private function bar()
    {
        return $this;
    }

    private function baz()
    {
        return $this;
    }

    private function bat()
    {
        return $this;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

class WithFluentNoReturnNeverCalled
{
    public function foo()
    {
        return $this
            ->bar()
            ->bat();
    }

    private function bar()
    {
        return $this;
    }

    private function bat()
    {
        return $this;
    }
}

?>