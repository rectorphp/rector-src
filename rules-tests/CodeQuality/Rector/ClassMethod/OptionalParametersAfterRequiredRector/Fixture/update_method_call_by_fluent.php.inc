<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class UpdateMethodCallByFluent
{
    public function run($optional = 1, $required)
    {
        return $this;
    }

    public function execute()
    {
    }

    public function process()
    {
        $this->run(1, 5)
             ->execute();
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class UpdateMethodCallByFluent
{
    public function run($required, $optional = 1)
    {
        return $this;
    }

    public function execute()
    {
    }

    public function process()
    {
        $this->run(5, 1)
             ->execute();
    }
}

?>
