<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\DefinedClass;

final class AddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\DefinedClass;

#[\AllowDynamicProperties]
final class AddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
