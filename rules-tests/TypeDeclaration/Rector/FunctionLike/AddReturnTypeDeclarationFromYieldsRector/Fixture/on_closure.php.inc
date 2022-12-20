<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

$func = function (): iterable {
    yield 1;
};

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

$func = function (): \Iterator {
    yield 1;
};

?>
