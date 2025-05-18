<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class IssetOnNullableTypedProperty
{
    private ?int $age = null;

    public function run()
    {
        return isset($this->age);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class IssetOnNullableTypedProperty
{
    private ?int $age = null;

    public function run()
    {
        return $this->age !== null;
    }
}

?>
