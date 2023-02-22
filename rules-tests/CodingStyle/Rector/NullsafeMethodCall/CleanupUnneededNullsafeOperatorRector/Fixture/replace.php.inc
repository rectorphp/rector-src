<?php

namespace Rector\Tests\CodingStyle\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

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

echo $this->get()?->getReplace();

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

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

echo $this->get()->getReplace();

?>
