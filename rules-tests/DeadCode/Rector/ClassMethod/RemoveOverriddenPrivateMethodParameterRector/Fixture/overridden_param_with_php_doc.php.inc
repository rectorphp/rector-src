<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class OverriddenParamWithPhpDoc
{
    public function run()
    {
        $this->prepare(new \stdClass());
    }

    /**
     * @param \stdClass $user
     */
    private function prepare(\stdClass $user): void
    {
        $user = new \stdClass();

        echo $user->name;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class OverriddenParamWithPhpDoc
{
    public function run()
    {
        $this->prepare();
    }

    private function prepare(): void
    {
        $user = new \stdClass();
        echo $user->name;
    }
}

?>
