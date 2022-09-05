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
     * @return mixed
     * @param object $object
     */
    public function resolveValue($object)
    {
        return true;
    }
}

class ChildClassTyped extends ParentClassTyped
{
    /**
     * @return mixed
     * @param object $object
     */
    public function resolveValue($object)
    {
        return false;
    }
}

?>
