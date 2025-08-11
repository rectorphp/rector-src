<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

final class SkipDifferentMethod
{
    public function differentMethod()
    {
        return null;
    }
}

?>
