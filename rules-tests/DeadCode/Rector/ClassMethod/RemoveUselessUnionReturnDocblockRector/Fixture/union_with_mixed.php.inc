<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class UnionWithMixed
{
    /**
     * @return bool|mixed|string
     */
    protected function setContactToSync(&$checkEmailsInSF, $lead): false|string
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class UnionWithMixed
{
    protected function setContactToSync(&$checkEmailsInSF, $lead): false|string
    {
    }
}

?>
