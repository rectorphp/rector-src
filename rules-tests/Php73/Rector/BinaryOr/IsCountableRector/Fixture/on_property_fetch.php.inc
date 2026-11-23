<?php

namespace Rector\Tests\Php73\Rector\BinaryOr\IsCountableRector\Fixture;

class OnPropertyFetch
{
    private $items;

    public function go()
    {
        is_array($this->items) || $this->items instanceof \Countable;
        is_array($this->items['key']) || $this->items['key'] instanceof \Countable;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\BinaryOr\IsCountableRector\Fixture;

class OnPropertyFetch
{
    private $items;

    public function go()
    {
        is_countable($this->items);
        is_countable($this->items['key']);
    }
}

?>
