<?php

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\FirstNamespace\SomeServiceClass;

class RenameClassNotInsideNamespace
{
    public function aFunction()
    {
        $someService = new SomeServiceClass();
    }
}

?>
-----
<?php

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\SecondNamespace\SomeServiceClass;

class RenameClassNotInsideNamespace
{
    public function aFunction()
    {
        $someService = new SomeServiceClass();
    }
}

?>
