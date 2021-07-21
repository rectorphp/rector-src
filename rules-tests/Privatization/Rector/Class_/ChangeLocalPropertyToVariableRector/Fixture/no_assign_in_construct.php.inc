<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeLocalPropertyToVariableRector\Fixture;

final class NoAssignInConstruct
{
    private string $name;

    public function __construct()
    {
    }

    public function sayHello(string $name)
    {
        $this->name = $name;
        return "Hello {$this->name}";
    }
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeLocalPropertyToVariableRector\Fixture;

final class NoAssignInConstruct
{
    public function __construct()
    {
    }

    public function sayHello(string $name)
    {
        $name = $name;
        return "Hello {$name}";
    }
}

?>
