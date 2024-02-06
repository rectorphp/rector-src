<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

class Fixture
{
    public function set()
    {
        $this->value = 5;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

#[\AllowDynamicProperties]
class Fixture
{
    public function set()
    {
        $this->value = 5;
    }
}

?>
