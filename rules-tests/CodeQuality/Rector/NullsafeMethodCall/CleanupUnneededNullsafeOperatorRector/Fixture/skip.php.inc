<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class Skip
{
    public function getString(): string
    {
        return 'hello world';
    }
}

function get(): ?Replace
{
    return (rand(0, 1)) ? new Replace() : null;
}

echo get()?->getString();

?>
