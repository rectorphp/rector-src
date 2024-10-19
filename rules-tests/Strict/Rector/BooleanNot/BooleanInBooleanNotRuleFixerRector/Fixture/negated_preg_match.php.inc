<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector\Fixture;

final class NegatedPregMatch
{
    public function run(string $param): bool
    {
        if (!preg_match('/^.+$/', $param)) {
	        return true;
        }
        return false;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector\Fixture;

final class NegatedPregMatch
{
    public function run(string $param): bool
    {
        if (in_array(preg_match('/^.+$/', $param), [0, false], true)) {
	        return true;
        }
        return false;
    }
}

?>
