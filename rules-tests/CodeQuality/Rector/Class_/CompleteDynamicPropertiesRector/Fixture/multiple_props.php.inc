<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

class MultipleProps
{
    public $a, $value;

    public function set()
    {
        $this->c = 5;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

class MultipleProps
{
    /**
     * @var int
     */
    public $c;
    public $a, $value;

    public function set()
    {
        $this->c = 5;
    }
}

?>
