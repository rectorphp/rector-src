<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

function foo($item) {
    function bar($item) {
        return $item . ' world';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

function foo($item) {
    function bar(string $item) {
        return $item . ' world';
    }
}

?>
