<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class ReplaceOnMethodCall
{
    public function getString(): string
    {
        return 'hello world';
    }

    /**
     * @return InvalidReturn
     */
    public function get(): Replace
    {
        return new Replace();
    }
}

(new ReplaceOnMethodCall())->get()?->getString();

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class ReplaceOnMethodCall
{
    public function getString(): string
    {
        return 'hello world';
    }

    /**
     * @return InvalidReturn
     */
    public function get(): Replace
    {
        return new Replace();
    }
}

(new ReplaceOnMethodCall())->get()->getString();

?>
