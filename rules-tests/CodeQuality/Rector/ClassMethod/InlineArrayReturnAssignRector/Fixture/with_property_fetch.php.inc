<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class WithPropertyFetch
{
    private string $name = 'test';

    public function foo($obj): array
    {
        $event['name'] = $this->name;
        $event['payload'] = $this->bar();
        $event['data'] = $obj?->data;
        $event['item'] = $obj?->getItem();
        $event['list'] = clone $obj;
        $event['verify'] = $obj instanceof \stdClass;

        return $event;
    }

    private function bar(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class WithPropertyFetch
{
    private string $name = 'test';

    public function foo($obj): array
    {
        return ['name' => $this->name, 'payload' => $this->bar(), 'data' => $obj?->data, 'item' => $obj?->getItem(), 'list' => clone $obj, 'verify' => $obj instanceof \stdClass];
    }

    private function bar(): array
    {
        return [];
    }
}

?>