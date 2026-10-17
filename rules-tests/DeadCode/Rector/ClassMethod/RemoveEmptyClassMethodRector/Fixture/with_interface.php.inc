<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source\ImplementedInterface;

final class WithInterface implements ImplementedInterface
{
    public function trample()
    {
    }

    public function go()
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source\ImplementedInterface;

final class WithInterface implements ImplementedInterface
{
    public function go()
    {
    }
}

?>
