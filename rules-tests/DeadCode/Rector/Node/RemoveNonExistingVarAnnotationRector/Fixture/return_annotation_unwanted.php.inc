<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

class ReturnAnnotationUnwanted
{
    public function getValue()
    {
        /** @var array<string, string> */
        return new stdClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

class ReturnAnnotationUnwanted
{
    public function getValue()
    {
        return new stdClass;
    }
}

?>
