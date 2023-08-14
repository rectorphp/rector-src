<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use Closure;

final class ClosureType
{
    private $handlers;

    public function __construct(Closure $handlers)
    {
        $this->handlers = $handlers;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use Closure;

final class ClosureType
{
    private \Closure $handlers;

    public function __construct(Closure $handlers)
    {
        $this->handlers = $handlers;
    }
}

?>
