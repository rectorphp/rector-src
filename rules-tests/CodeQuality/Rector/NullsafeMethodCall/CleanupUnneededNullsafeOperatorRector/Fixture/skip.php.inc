<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class SkipNullable
{
    public function getString(): string
    {
        return 'hello world';
    }
}

function run(): ?SkipNullable
{
    return (rand(0, 1)) ? new SkipNullable() : null;
}

echo run()?->getString();

?>
