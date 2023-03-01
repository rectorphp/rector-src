<?php

namespace Rector\Tests\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector\Fixture;

use PHPUnit_SpecialClass;

class PhpunitSpecialClass
{
    public function create()
    {
        $specialClass = new PHPUnit_SpecialClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector\Fixture;

use PHPUnit\SpecialClass;

class PhpunitSpecialClass
{
    public function create()
    {
        $specialClass = new \PHPUnit\SpecialClass;
    }
}

?>
