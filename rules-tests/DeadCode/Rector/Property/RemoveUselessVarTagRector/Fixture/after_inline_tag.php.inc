<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

final class AfterInlineTag
{
    /**
     * @internal Use @see \Rector\Config\RectorConfig::paths() instead
     * @var string
     */
    public const string NAME = 'value';
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

final class AfterInlineTag
{
    /**
     * @internal Use @see \Rector\Config\RectorConfig::paths() instead
     */
    public const string NAME = 'value';
}

?>
