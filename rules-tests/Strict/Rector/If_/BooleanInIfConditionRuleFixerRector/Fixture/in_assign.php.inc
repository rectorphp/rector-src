<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector\Fixture;

final class InAssign
{
    private function getList(): array
    {
        return [];
    }

    public function run()
    {

        if ($items = $this->getList()) {
            echo $items[0];
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector\Fixture;

final class InAssign
{
    private function getList(): array
    {
        return [];
    }

    public function run()
    {

        if (($items = $this->getList()) !== []) {
            echo $items[0];
        }
    }
}

?>
