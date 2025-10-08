<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class WithSubstr
{
    public function run(): string
    {
        $className = 'Exception';
        return (string) substr($className, 0, -strlen($className) - 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class WithSubstr
{
    public function run(): string
    {
        $className = 'Exception';
        return substr($className, 0, -strlen($className) - 1);
    }
}

?>