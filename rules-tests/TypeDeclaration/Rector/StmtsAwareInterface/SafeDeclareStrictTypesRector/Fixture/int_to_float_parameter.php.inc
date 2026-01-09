<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

use function Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source\acceptsFloat;

acceptsFloat(123);

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

use function Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source\acceptsFloat;

acceptsFloat(123);
