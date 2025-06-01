<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Source\SomeParentClass;

final class Fixture extends SomeParentClass
{
    final public function __construct(string $differentParam)
    {
        init($differentParam);

        parent::__construct([]);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Source\SomeParentClass;

final class Fixture extends SomeParentClass
{
    final public function __construct(string $differentParam)
    {
        init($differentParam);

        parent::__construct();
    }
}

?>
