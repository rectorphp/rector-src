<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

final class Collection
{
    private array $items;
    
    public function __construct()
    {
        $this->items = [];
    }

    public function count(): int
    {
        return count($this->items);
    }
}
?>
-----
<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

final class Collection
{
    private array $items;
    
    public function __construct()
    {
        $this->items = [];
    }

    public function count(): int
    {
        return count($this->items);
    }
}
?>
