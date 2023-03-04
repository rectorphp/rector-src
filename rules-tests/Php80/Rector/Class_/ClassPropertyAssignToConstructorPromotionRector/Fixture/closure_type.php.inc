<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class ClosureType
{
    private readonly \Closure $configureContainerBuilder;

    public function __construct(\Closure $configureContainerBuilder)
    {
        $this->configureContainerBuilder = $configureContainerBuilder;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class ClosureType
{
    public function __construct(private readonly \Closure $configureContainerBuilder)
    {
    }
}

?>