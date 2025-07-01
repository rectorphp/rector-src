<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

class ConstructorPrivate
{
    public function __construct()
    {
        $this->value = 'classStringCaseInSensitive';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

class ConstructorPrivate
{
    private string $value;
    public function __construct()
    {
        $this->value = 'classStringCaseInSensitive';
    }
}

?>
