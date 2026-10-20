<?php

namespace Rector\Tests\Issues\ClassScopeRefresh\Fixture;

final class KeepMethodScopeAfterClassChange
{
    public function __construct(
        private readonly int $value
    ) {
    }

    public function run(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result = [...$result, ...$items];
        }

        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassScopeRefresh\Fixture;

final class KeepMethodScopeAfterClassChange
{
    /**
     * @readonly
     */
    private int $value;
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function run(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result = array_merge($result, $items);
        }

        return $result;
    }
}

?>
