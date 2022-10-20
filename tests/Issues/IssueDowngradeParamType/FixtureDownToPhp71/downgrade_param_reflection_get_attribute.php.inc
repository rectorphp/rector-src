<?php

namespace Rector\Core\Tests\Issues\IssueDowngradeParamType\FixtureDownToPhp71;

use ReflectionClass;

class DowngradeParamReflectionGetAttribute
{
    public function run(ReflectionClass $reflectionClass)
    {
        echo $reflectionClass->getFileName();

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
        echo $reflectionClass->getFileName();

        if (method_exists($reflectionClass, 'getAttributes') ? $reflectionClass->getAttributes() : []) {
            return true;
        }
        return false;
    }
}

?>
