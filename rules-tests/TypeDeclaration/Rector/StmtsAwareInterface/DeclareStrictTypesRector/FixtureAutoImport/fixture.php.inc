<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector\FixtureAutoImport;

\Foo\Bar::BAZ;

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector\FixtureAutoImport;

use Foo\Bar;

\Foo\Bar::BAZ;
