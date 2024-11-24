<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PhpParser\Node as PHPParser;

final class AssignObjectAliased
{
    private $int;

    public function __construct()
    {
        $this->int = new PHPParser\Scalar\Int_(1);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PhpParser\Node as PHPParser;

final class AssignObjectAliased
{
    private \PhpParser\Node\Scalar\Int_ $int;

    public function __construct()
    {
        $this->int = new PHPParser\Scalar\Int_(1);
    }
}

?>
