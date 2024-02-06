<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

class SkipExtendingStdClass extends \stdClass
{
    public function set()
    {
        $this->value = 5;
    }
}

?>
