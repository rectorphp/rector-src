<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class MirrorCommentMultipleProperties
{
    /**
     * some existing comment
     */
    private $thing1, $thing2, $thing3;

    public function __construct()
    {
        // first
        $this->thing1 = 1;
        // second
        $this->thing2 = 2;
        // third
        $this->thing3 = 3;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class MirrorCommentMultipleProperties
{
    /**
     * some existing comment
     */
    // first
    // second
    // third
    private $thing1 = 1, $thing2 = 2, $thing3 = 3;

    public function __construct()
    {
    }
}

?>
