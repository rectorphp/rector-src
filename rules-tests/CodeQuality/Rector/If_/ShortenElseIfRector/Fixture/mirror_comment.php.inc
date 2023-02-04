<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

class MirrorComment
{
    public function run()
    {
        if (rand(0, 1)) {
        } else {
            // a comment
            if (rand(1, 2)) {
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

class MirrorComment
{
    public function run()
    {
        if (rand(0, 1)) {
        } elseif (rand(1, 2)) {
            // a comment
        }
    }
}

?>
