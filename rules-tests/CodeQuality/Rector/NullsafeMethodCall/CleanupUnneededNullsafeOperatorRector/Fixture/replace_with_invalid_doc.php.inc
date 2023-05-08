<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class ReplaceWithInvalidDoc
{
    public function getString(): string
    {
        return 'hello world';
    }
}

/**
 * @return ReplaceWithInvalidDoc|null
 */
function get(): ReplaceWithInvalidDoc
{
    return new ReplaceWithInvalidDoc();
}

echo $this->get()?->getReplaceWithInvalidDoc();

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\Fixture;

class ReplaceWithInvalidDoc
{
    public function getString(): string
    {
        return 'hello world';
    }
}

/**
 * @return ReplaceWithInvalidDoc|null
 */
function get(): ReplaceWithInvalidDoc
{
    return new ReplaceWithInvalidDoc();
}

echo $this->get()->getReplaceWithInvalidDoc();

?>
