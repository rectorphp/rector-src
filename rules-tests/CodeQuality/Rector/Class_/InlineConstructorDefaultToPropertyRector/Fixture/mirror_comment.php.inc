<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class MirrorComment
{
    private $thing;

    public function __construct()
    {
        /** some important comment */
        $this->thing = 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class MirrorComment
{
    /** some important comment */
    private $thing = 1;

    public function __construct()
    {
    }
}

?>
