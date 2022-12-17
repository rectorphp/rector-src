<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

use Exception;

class BadRequest extends Exception {

}

$badRequest = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequest();

if ($badRequest instanceof \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequest) {
    echo \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequest::class;
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

use Exception;

class BadRequestException extends Exception {

}

$badRequest = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequestException();

if ($badRequest instanceof \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequestException) {
    echo \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequestException::class;
}

?>
