<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

function ($item) {
    return is_string($item);
};

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

function ($item): bool {
    return is_string($item);
};

?>
