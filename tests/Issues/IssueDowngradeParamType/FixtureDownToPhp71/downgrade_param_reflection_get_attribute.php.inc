<?php

namespace Rector\Core\Tests\Issues\IssueDowngradeParamType\FixtureDownToPhp71;

use ReflectionClass;

class DowngradeParamReflectionGetAttribute
{
    public function run(ReflectionClass $reflectionClass)
    {
        if ($reflectionClass->getAttributes()) {
            return true;
        }
        return false;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueDowngradeParamType\FixtureDownToPhp71;

use ReflectionClass;

class DowngradeParamReflectionGetAttribute
{
    /**
     * @param \ReflectionClass $reflectionClass
     */
    public function run($reflectionClass)
    {
        if (method_exists($reflectionClass, 'getAttributes') ? $reflectionClass->getAttributes() : []) {
            return true;
        }
        return false;
    }
}

?>
