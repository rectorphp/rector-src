<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PhpParser\Node;

final class AssignObjectSubnamespace
{
    private $int;

    public function __construct()
    {
        $this->int = new Node\Scalar\Int_(1);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use PhpParser\Node;

final class AssignObjectSubnamespace
{
    private \PhpParser\Node\Scalar\Int_ $int;

    public function __construct()
    {
        $this->int = new Node\Scalar\Int_(1);
    }
}

?>
