<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector\Fixture;

final class ThrowAfterIf
{
    protected ?\stdClass $prop1 = null;
    protected ?\stdClass $prop2 = null;

    public function getProp(): \stdClass
    {
        if ($this->prop1 !== null) {
            return $this->prop1;
        }
        if ($this->prop2 !== null) {
            return $this->prop2;
        }
        throw new \RuntimeException('No prop is set');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector\Fixture;

final class ThrowAfterIf
{
    protected ?\stdClass $prop1 = null;
    protected ?\stdClass $prop2 = null;

    public function getProp(): \stdClass
    {
        return ($this->prop1 ?? $this->prop2) ?? throw new \RuntimeException('No prop is set');
    }
}

?>
