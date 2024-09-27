<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

class UsedAsAssignExpr
{
    public function __construct(private string $foo)
    {}

    public function run()
    {
        $data['data'] = $this->foo;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

class UsedAsAssignExpr
{
    public function __construct(private readonly string $foo)
    {}

    public function run()
    {
        $data['data'] = $this->foo;
    }
}

?>
