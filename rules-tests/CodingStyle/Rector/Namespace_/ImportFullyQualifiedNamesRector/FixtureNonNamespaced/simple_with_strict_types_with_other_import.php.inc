<?php

declare(strict_types=1);

use Foo\Baz;

function simpleWithStrictTypesWithOtherImport() {
    $baz = new \Foo\Bar;
}

?>
-----
<?php

declare(strict_types=1);

use Foo\Bar;

use Foo\Baz;

function simpleWithStrictTypesWithOtherImport() {
    $baz = new Bar;
}

?>
