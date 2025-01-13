<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class WithAssign
{
    private function getExpiredAt(): \Datetime|null
    {
        return null;
    }

    public function run(string $item)
    {
        if ($expired = $this->getExpiredAt()) {
            echo $expired->format('Ymd');
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class WithAssign
{
    private function getExpiredAt(): \Datetime|null
    {
        return null;
    }

    public function run(string $item)
    {
        if (($expired = $this->getExpiredAt()) !== null) {
            echo $expired->format('Ymd');
        }
    }
}

?>
