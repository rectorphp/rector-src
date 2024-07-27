<?php

declare(strict_types=1);

use Foo\Baz;

function simpleWithStrictTypesWithOtherImport() {
    $baz = new \Foo\Bar;
    $bat = new \Foo\Bat;
}

?>
-----
<?php

declare(strict_types=1);

use Foo\Bar;
use Foo\Bat;

use Foo\Baz;

function simpleWithStrictTypesWithOtherImport() {
    $baz = new Bar;
    $bat = new Bat;
}

?>
