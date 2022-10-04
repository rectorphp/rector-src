<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class NullableBool
{
    public function run(bool|null $value)
    {
        return empty($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class NullableBool
{
    public function run(bool|null $value)
    {
        return $value !== true;
    }
}

?>
