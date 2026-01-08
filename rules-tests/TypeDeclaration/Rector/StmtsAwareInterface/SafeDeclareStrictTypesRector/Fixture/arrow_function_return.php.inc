<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

$arrow = fn (): int => 42;

$arrowWithParam = fn (int $x): int => $x + 1;

$arrowString = fn (): string => 'hello';

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

$arrow = fn (): int => 42;

$arrowWithParam = fn (int $x): int => $x + 1;

$arrowString = fn (): string => 'hello';
