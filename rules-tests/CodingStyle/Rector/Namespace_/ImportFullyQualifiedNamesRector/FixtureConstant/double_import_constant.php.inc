<?php

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\FixtureConstant;

class DoubleImportConstant
{
    public function create()
    {
        return \Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Source\Constant_\MY_BEAUTIFUL_CONSTANT;
    }

    public function emulate()
    {
        return \Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Source\Constant_\MY_BEAUTIFUL_CONSTANT;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\FixtureConstant;

use const Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Source\Constant_\MY_BEAUTIFUL_CONSTANT;

class DoubleImportConstant
{
    public function create()
    {
        return MY_BEAUTIFUL_CONSTANT;
    }

    public function emulate()
    {
        return MY_BEAUTIFUL_CONSTANT;
    }
}

?>
