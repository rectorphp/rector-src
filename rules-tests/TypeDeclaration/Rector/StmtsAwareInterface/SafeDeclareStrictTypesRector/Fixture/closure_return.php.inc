<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

$closure = function (): int {
    return 42;
};

$closureWithParam = function (int $x): int {
    return $x + 1;
};

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

$closure = function (): int {
    return 42;
};

$closureWithParam = function (int $x): int {
    return $x + 1;
};
