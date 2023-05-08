<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class Replace
{
    public function getString(): string
    {
        return 'hello world';
    }
}

function get(): Replace
{
    return new Replace();
}

echo get()?->getReplace();

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class Replace
{
    public function getString(): string
    {
        return 'hello world';
    }
}

function get(): Replace
{
    return new Replace();
}

echo get()->getReplace();

?>
