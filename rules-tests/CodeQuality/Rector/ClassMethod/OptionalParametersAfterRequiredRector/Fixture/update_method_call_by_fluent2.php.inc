<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class UpdateMethodCallByFluent2
{
    public function execute(): self
    {
        return $this;
    }

    public function run($optional = 1, $required)
    {
        return $this;
    }

    public function process()
    {
        $this->execute()
             ->run(1, 5);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class UpdateMethodCallByFluent2
{
    public function execute(): self
    {
        return $this;
    }

    public function run($required, $optional = 1)
    {
        return $this;
    }

    public function process()
    {
        $this->execute()
             ->run(5, 1);
    }
}

?>
