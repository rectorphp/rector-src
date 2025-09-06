<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class UpdateMethodCall
{
    public function run($optional = 1, $required)
    {
    }

    public function process()
    {
        $this->run(1, 5);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class UpdateMethodCall
{
    public function run($optional = 1, $required = null)
    {
    }

    public function process()
    {
        $this->run(1, 5);
    }
}

?>
