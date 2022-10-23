<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

// when there is no \mysqli class, it may pull from scoper phar file
final class ExtendsMysqli extends \mysqli
{
    public function query($query) {
        //Code
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

// when there is no \mysqli class, it may pull from scoper phar file
final class ExtendsMysqli extends \mysqli
{
    public function query($query,
    #[\JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable(from: '7.1')]
    int $result_mode = \MYSQLI_STORE_RESULT) {
        //Code
    }
}

?>
