<?php

namespace Rector\Tests\Php82\Rector\Class_\AllowDynamicPropertiesRector\Fixture;

#[\Immutable]
class SomeObject
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\AllowDynamicPropertiesRector\Fixture;

#[\Immutable]
#[\AllowDynamicProperties]
class SomeObject
{
    public string $someProperty = 'hello world';
}

?>
