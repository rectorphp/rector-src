<?php

namespace Rector\Tests\TypeDeclaration\Rector\Function_\AddFunctionVoidReturnTypeWhereNoReturnRector\Fixture;

function some_function()
{
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Function_\AddFunctionVoidReturnTypeWhereNoReturnRector\Fixture;

function some_function(): void
{
}

?>
