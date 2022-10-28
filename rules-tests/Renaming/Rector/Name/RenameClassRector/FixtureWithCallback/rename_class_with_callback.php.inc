<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

use Exception;

class BadRequest extends Exception {

}

$badRequest = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequest();

class CorrectlyNamedException extends Exception {

}

$correctlyNamedException = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\CorrectlyNamedException();

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

use Exception;

class BadRequestException extends Exception {

}

$badRequest = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequestException();

class CorrectlyNamedException extends Exception {

}

$correctlyNamedException = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\CorrectlyNamedException();

?>
