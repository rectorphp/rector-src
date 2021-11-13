<?php

namespace Rector\Tests\Php82\Rector\Class_\AllowDynamicPropertiesRector\Fixture;

#[\AllowDynamicProperties]
class AnotherObject
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\AllowDynamicPropertiesRector\Fixture;

#[\AllowDynamicProperties]
class AnotherObject
{
    public string $someProperty = 'hello world';
}

?>
