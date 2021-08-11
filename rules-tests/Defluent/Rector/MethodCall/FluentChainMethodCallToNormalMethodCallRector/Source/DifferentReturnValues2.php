<?php

declare(strict_types=1);

namespace Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Source;

final class DifferentReturnValues2 implements FluentInterfaceClassInterface
{
    public function someFunction(): self
    {
        return $this;
    }

    public function otherFunction(): int
    {
        return 5;
    }
}
