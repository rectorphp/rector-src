<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

$fn = strlen(...);

$upperFn = strtoupper(...);

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

$fn = strlen(...);

$upperFn = strtoupper(...);
