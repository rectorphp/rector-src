<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

class NamedArgumentSamePosition
{
    public function run(bool $param): void
    {
        $this->isTrue(text: 'test', value: $param);
    }

    private function isTrue(string $text, $value = true): bool
    {
        return $text === 'test';
    }
}

?>
----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

class NamedArgumentSamePosition
{
    public function run(bool $param): void
    {
        $this->isTrue(text: 'test');
    }

    private function isTrue(string $text): bool
    {
        return $text === 'test';
    }
}

?>