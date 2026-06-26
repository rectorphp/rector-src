<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockRector\Fixture;

final class RemoveParamAndReturnMixed
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function run(int $value): string
    {
        return (string) $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockRector\Fixture;

final class RemoveParamAndReturnMixed
{
    public function run(int $value): string
    {
        return (string) $value;
    }
}

?>
