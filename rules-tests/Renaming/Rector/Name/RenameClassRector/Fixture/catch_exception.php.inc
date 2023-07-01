<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class CatchException
{
    public function run()
    {
        try {

        } catch (\Exception $e) {}
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class CatchException
{
    public function run()
    {
        try {

        } catch (\RuntimeException $e) {}
    }
}

?>
