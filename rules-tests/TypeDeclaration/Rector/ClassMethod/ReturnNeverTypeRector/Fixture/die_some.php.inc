<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

final class DieSome
{
    public function run()
    {
        echo 100;
        die;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

final class DieSome
{
    /**
     * @return never
     */
    public function run()
    {
        echo 100;
        die;
    }
}

?>
