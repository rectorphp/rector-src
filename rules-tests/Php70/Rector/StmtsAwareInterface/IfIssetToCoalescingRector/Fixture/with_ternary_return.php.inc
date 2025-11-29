<?php

namespace Rector\Tests\Php70\Rector\StmtsAwareInterface\IfIssetToCoalescingRector\Fixture;

class WithTernaryReturn
{
    private $values = [];
    private $nullable = false;

    public function resolve($param)
    {
        if (isset($this->values[$param])) {
            return $this->values[$param];
        }

        return $this->nullable ? null : false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\StmtsAwareInterface\IfIssetToCoalescingRector\Fixture;

class WithTernaryReturn
{
    private $values = [];
    private $nullable = false;

    public function resolve($param)
    {
        return $this->values[$param] ?? ($this->nullable ? null : false);
    }
}

?>
