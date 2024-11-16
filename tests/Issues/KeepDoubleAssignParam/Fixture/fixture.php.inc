<?php

namespace Rector\Tests\Issues\KeepDoubleAssignParam\Fixture;

final class Fixture
{
    private $items;

    public function __construct($input)
    {
        if (! \is_array($input)) {
            $this->items = [$input];
        } else {
            $this->items = $input;
        }

        $this->items = $this->getItems();
    }

    public function getItems()
    {
        return sort($this->items);
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\KeepDoubleAssignParam\Fixture;

final class Fixture
{
    private $items;

    public function __construct($input)
    {
        $this->items = ! \is_array($input) ? [$input] : $input;

        $this->items = $this->getItems();
    }

    public function getItems()
    {
        return sort($this->items);
    }
}

?>
