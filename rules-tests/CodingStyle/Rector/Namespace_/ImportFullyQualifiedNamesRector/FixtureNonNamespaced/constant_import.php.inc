<?php

class ImportConstantWithoutNamespace
{
    public function run()
    {
        return \Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Source\Constant_\MY_BEAUTIFUL_CONSTANT;
    }
}

?>
-----
<?php

use const Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Source\Constant_\MY_BEAUTIFUL_CONSTANT;

class ImportConstantWithoutNamespace
{
    public function run()
    {
        return MY_BEAUTIFUL_CONSTANT;
    }
}
