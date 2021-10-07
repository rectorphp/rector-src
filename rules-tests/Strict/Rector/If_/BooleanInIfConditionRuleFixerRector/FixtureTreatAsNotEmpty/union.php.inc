<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector\FixtureTreatAsNonEmpty;

use stdClass;

function (string|int|null|stdClass $mix = null)
{
    if ($mix) {
    }
};

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector\FixtureTreatAsNonEmpty;

use stdClass;

function (string|int|null|stdClass $mix = null)
{
    if ($mix !== 0 && $mix !== '' && $mix !== null) {
    }
};

?>
