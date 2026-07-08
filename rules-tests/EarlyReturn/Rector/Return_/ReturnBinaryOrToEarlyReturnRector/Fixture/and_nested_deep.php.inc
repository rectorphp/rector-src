<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector\Fixture;

class AndNestedDeep
{
    public function hasStale(): bool
    {
        return $this->donation === null
            || ($this->commission > 0 && $this->transaction === null)
            || $this->somethingElse()
            || $this->anotherThing();
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector\Fixture;

class AndNestedDeep
{
    public function hasStale(): bool
    {
        if ($this->donation === null) {
            return true;
        }
        if ($this->commission > 0 && $this->transaction === null) {
            return true;
        }
        if ($this->somethingElse()) {
            return true;
        }
        return (bool) $this->anotherThing();
    }
}

?>
