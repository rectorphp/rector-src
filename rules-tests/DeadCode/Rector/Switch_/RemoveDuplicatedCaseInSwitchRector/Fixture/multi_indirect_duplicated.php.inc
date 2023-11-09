<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class MultiIndirectDuplicated
{
    public function run()
    {
        switch ($name) {
            case 'clearAllHeaders':
                return $this->modifyHeader($node, 'replace');
            case 'clearHeader':
                return $this->modifyHeader($node, 'remove');
            case 'clearRawHeaders':
                return $this->modifyHeader($node, 'replace');
            case 'clearRawHeaders2':
                return $this->modifyHeader($node, 'replace');
            case '...':
                return 5;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class MultiIndirectDuplicated
{
    public function run()
    {
        switch ($name) {
            case 'clearAllHeaders':
            case 'clearRawHeaders':
            case 'clearRawHeaders2':
                return $this->modifyHeader($node, 'replace');
            case 'clearHeader':
                return $this->modifyHeader($node, 'remove');
            case '...':
                return 5;
        }
    }
}

?>
