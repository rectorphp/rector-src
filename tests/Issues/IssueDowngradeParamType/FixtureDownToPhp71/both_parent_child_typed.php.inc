<?php

namespace Rector\Core\Tests\Issues\IssueDowngradeParamType\FixtureDownToPhp71;

class ParentClassTyped
{
    public function resolveValue(object $object,): mixed
    {
        return true;
    }
}

class ChildClassTyped extends ParentClassTyped
{
    public function resolveValue(object $object,): mixed
    {
        return false;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueDowngradeParamType\FixtureDownToPhp71;

class ParentClassTyped
{
    /**
     * @param object $object
     * @return mixed
     */
    public function resolveValue($object)
    {
        return true;
    }
}

class ChildClassTyped extends ParentClassTyped
{
    /**
     * @param object $object
     * @return mixed
     */
    public function resolveValue($object)
    {
        return false;
    }
}

?>
