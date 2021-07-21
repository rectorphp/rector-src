<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeLocalPropertyToVariableRector\Fixture;

final class NoAssignInConstructHasDefaultValue
{
    private string $name = 'test';

    public function __construct()
    {
    }

    public function sayHello()
    {
        return "Hello {$this->name}";
    }
}

?>
