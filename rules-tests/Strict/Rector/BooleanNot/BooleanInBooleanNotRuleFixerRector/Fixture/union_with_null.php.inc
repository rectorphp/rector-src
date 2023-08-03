<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector\Fixture;

final class UnionWithNull
{
    private string|null $value = null;

    public function run()
    {
        if (! $this->value) {
            return 'empty';
        }

        return 'set';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector\Fixture;

final class UnionWithNull
{
    private string|null $value = null;

    public function run()
    {
        if ($this->value === null || $this->value === '') {
            return 'empty';
        }

        return 'set';
    }
}

?>
