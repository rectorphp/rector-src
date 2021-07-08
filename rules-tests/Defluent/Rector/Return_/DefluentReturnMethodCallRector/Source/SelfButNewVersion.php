<?php

declare(strict_types=1);

namespace Rector\Tests\Defluent\Rector\Return_\DefluentReturnMethodCallRector\Source;

final class SelfStaticButNewVersion
{
    public function duplicate(): self
    {
        return new self();
    }

    public function duplicate2(): self
    {
        return new static();
    }

    public function duplicate3(): self
    {
        return new $this;
    }
}
