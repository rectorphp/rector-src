<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

use Exception;

class BadRequest extends Exception {

}

$badRequest = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequest();

if ($badRequest instanceof \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequest) {
    echo \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\BadRequest::class;
}

class CorrectlyNamedException extends Exception {

}

$correctlyNamedException = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\CorrectlyNamedException();

interface Processor {

}

class FruitProcessor implements \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\Processor {

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

class CorrectlyNamedException extends Exception {

}

$correctlyNamedException = new \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\CorrectlyNamedException();

interface ProcessorInterface {

}

class FruitProcessor implements \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\ProcessorInterface {

}

?>
