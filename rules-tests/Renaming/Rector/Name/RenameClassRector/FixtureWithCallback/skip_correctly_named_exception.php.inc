<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

use Exception;

class CorrectlyNamedException extends Exception {

}

$correctlyNamedException = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\CorrectlyNamedException();

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

use Exception;

class CorrectlyNamedException extends Exception {

}

$correctlyNamedException = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\CorrectlyNamedException();

?>
