<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

class MirrorComment2
{
    public function run()
    {
        if (rand(0, 1)) {
        } else {
            // a comment
            if (rand(1, 2)) {
                // other comment
                echo 'some statement';
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

class MirrorComment2
{
    public function run()
    {
        if (rand(0, 1)) {
        } elseif (rand(1, 2)) {
            // a comment
            // other comment
            echo 'some statement';
        }
    }
}

?>