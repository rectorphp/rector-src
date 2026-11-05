<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class RemoveWithArrayCallableOfOtherClass
{
    public function run()
    {
        $this->format('unused', 'value');

        return [\DateTime::class, 'format'];
    }

    private function format(string $unused, string $value): string
    {
        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class RemoveWithArrayCallableOfOtherClass
{
    public function run()
    {
        $this->format('value');

        return [\DateTime::class, 'format'];
    }

    private function format(string $value): string
    {
        return $value;
    }
}

?>
