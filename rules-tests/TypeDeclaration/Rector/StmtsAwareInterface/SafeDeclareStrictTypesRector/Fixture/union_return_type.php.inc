<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function returnsStringOrInt(bool $flag): string|int
{
    if ($flag) {
        return 'hello';
    }
    return 42;
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function returnsStringOrInt(bool $flag): string|int
{
    if ($flag) {
        return 'hello';
    }
    return 42;
}
