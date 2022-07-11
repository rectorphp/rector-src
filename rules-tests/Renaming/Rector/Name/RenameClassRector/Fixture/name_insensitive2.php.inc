<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClassWithTypo2;

class NameInsensitive2 extends OldClassWithTypO2
{
    public function run(): OLDClassWithTYPO2
    {
        $oldClassWithTypo = new OldClassWithTYPO2;
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClassWithTypo2;

class NameInsensitive2 extends \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo2
{
    public function run(): \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo2
    {
        $oldClassWithTypo = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo2;
    }
}

?>
