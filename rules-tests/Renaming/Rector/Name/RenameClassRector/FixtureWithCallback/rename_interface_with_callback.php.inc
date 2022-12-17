<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

interface Processor {

}

class FruitProcessor implements \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\Processor {

}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback;

interface ProcessorInterface {

}

class FruitProcessor implements \Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureWithCallback\ProcessorInterface {

}

?>
