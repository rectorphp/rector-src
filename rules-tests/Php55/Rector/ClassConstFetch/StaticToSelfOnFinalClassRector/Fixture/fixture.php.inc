<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector\Fixture;

final class Fixture
{
    public function callOnMe()
    {
        var_dump(static::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector\Fixture;

final class Fixture
{
    public function callOnMe()
    {
        var_dump(self::class);
    }
}

?>
