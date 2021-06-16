<?php

namespace Rector\EarlyReturn\Tests\Rector\If_\RemoveAlwaysElseRector\Fixture;

class LostCommentBeforeElseif3
{
    public function convert($data)
    {
        // a comment 1
        if (is_array($data)) {
            return $res;
        }
        // a comment 2
        elseif ($data instanceof EncodedString) {
        }
        // a comment 3
        elseif (is_string($data)) {
        }
        return $data;
    }
}

?>
-----
<?php

namespace Rector\EarlyReturn\Tests\Rector\If_\RemoveAlwaysElseRector\Fixture;

class LostCommentBeforeElseif3
{
    public function convert($data)
    {
        // a comment 1
        if (is_array($data)) {
            return $res;
        }
        // a comment 2
        if ($data instanceof EncodedString) {
        }
        // a comment 3
        elseif (is_string($data)) {
        }
        return $data;
    }
}

?>
