<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class ReplaceOnMethodCall
{
    public function getString2(): string
    {
        return 'hello world';
    }

    /**
     * @return InvalidReturn
     */
    public function get2(): Replace
    {
        return new Replace();
    }
}

(new ReplaceOnMethodCall())->get2()?->getString2();

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class ReplaceOnMethodCall
{
    public function getString2(): string
    {
        return 'hello world';
    }

    /**
     * @return InvalidReturn
     */
    public function get2(): Replace
    {
        return new Replace();
    }
}

(new ReplaceOnMethodCall())->get2()->getString2();

?>
