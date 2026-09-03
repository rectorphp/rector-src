<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

class KeepCommentInsideIf
{
    public function get($something)
    {
        if ($something === null) {
            /* @phpstan-ignore some.rule */
            $something = 'something';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

class KeepCommentInsideIf
{
    public function get($something)
    {
        /* @phpstan-ignore some.rule */
        $something ??= 'something';
    }
}

?>
