<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class SkipNullableCallable
{
    private $prop;

    public function set(callable $prop): void
    {
        $this->prop = $prop;
    }

    public function reset(): void
    {
        $this->prop = null;
    }
}
