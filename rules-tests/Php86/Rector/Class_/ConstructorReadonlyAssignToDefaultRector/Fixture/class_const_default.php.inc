<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class ClassConstDefault
{
    private const DEFAULT_NAME = 'value';

    public readonly string $name;

    public function __construct()
    {
        $this->name = self::DEFAULT_NAME;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class ClassConstDefault
{
    private const DEFAULT_NAME = 'value';

    public readonly string $name = self::DEFAULT_NAME;

    public function __construct()
    {
    }
}

?>
