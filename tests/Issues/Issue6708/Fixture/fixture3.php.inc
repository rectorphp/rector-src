<?php

namespace Rector\Core\Tests\Issues\Issue6708\Fixture;

class Fixture3
{
    function getArray(): array
    {
        return ['apple', 'pear'];
    }

    public function run($operation)
    {
        return match ($operation) {
            'fruits' => ['banana', 'orange', ...$this->getFirstArray()],
            'veggies' => ['tomato', 'lettuce', ...$this->getSecondArray()],
        };
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue6708\Fixture;

class Fixture3
{
    function getArray(): array
    {
        return ['apple', 'pear'];
    }

    public function run($operation)
    {
        switch ($operation) {
            case 'fruits':
                return array_merge(['banana', 'orange'], is_array($this->getFirstArray()) ? $this->getFirstArray() : iterator_to_array($this->getFirstArray()));
            case 'veggies':
                return array_merge(['tomato', 'lettuce'], is_array($this->getSecondArray()) ? $this->getSecondArray() : iterator_to_array($this->getSecondArray()));
        }
    }
}

?>
