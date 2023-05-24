<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveUnusedVariableDefault\Fixture;

final class ReUsedAssign
{
    public function testCastAsJSONErrorDepth()
    {
        $entity = $this->getCastEntity();

        // Create array with depth 513 to get depth error
        $array   = [];
        $value   = 'test value';
        $keys    = rtrim(str_repeat('test.', 513), '.');
        $keys    = explode('.', $keys);
        $current = $array;

        foreach ($keys as $key) {
            $current = $current[$key];
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveUnusedVariableDefault\Fixture;

final class ReUsedAssign
{
    public function testCastAsJSONErrorDepth()
    {
        $entity = $this->getCastEntity();

        // Create array with depth 513 to get depth error
        $array   = [];
        $keys    = rtrim(str_repeat('test.', 513), '.');
        $keys    = explode('.', $keys);
        $current = $array;

        foreach ($keys as $key) {
            $current = $current[$key];
        }
    }
}

?>
