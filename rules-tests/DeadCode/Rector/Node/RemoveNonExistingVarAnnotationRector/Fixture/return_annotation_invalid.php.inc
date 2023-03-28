<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

class ReturnAnnotationInvalid
{
    public function getValue()
    {
        /** @var int */
        return;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

class ReturnAnnotationInvalid
{
    public function getValue()
    {
        return;
    }
}

?>
