<?php

namespace Rector\Tests\CodingStyle\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class Skip
{
    public function getString(): string
    {
        return 'hello world';
    }
}

function get(): ?Replace
{
    return new Replace();
}

echo $this->get()?->getReplace();

?>
