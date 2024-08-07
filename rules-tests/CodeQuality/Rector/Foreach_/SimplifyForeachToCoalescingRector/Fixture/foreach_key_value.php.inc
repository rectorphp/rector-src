<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

class ForeachKeyValue
{
    /**
     * @var mixed[]
     */
    private array $oldToNewOption = [];

    public function run()
    {
        $currentOptionName = 5;
        $stringKeyNode = new \stdClass();

        foreach ($this->oldToNewOption as $oldOption => $newOption) {
            if ($currentOptionName === $oldOption) {
                $stringKeyNode->value = $newOption;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

class ForeachKeyValue
{
    /**
     * @var mixed[]
     */
    private array $oldToNewOption = [];

    public function run()
    {
        $currentOptionName = 5;
        $stringKeyNode = new \stdClass();

        $stringKeyNode->value = $this->oldToNewOption[$currentOptionName] ?? $stringKeyNode->value;
    }
}

?>
