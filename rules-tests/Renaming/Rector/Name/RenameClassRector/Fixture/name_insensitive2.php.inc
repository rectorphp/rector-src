<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClassWithTypo2;

class NameInsensitive2 extends OldClassWithTypo2
{
    public function run(): OldClassWithTypo2
    {
        $oldClassWithTypo = new OldClassWithTypo2;
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
