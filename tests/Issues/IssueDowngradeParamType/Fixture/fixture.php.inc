<?php

namespace Rector\Core\Tests\Issues\IssueDowngradeParamType\Fixture;

class ParentClass
{
    public function execute(string $param,): void
    {
    }
}

class ChildClass extends ParentClass
{
    public function execute($param): void
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueDowngradeParamType\Fixture;

class ParentClass
{
    /**
     * @param string $param
     */
    public function execute($param): void
    {
    }
}

class ChildClass extends ParentClass
{
    public function execute($param): void
    {
    }
}

?>
