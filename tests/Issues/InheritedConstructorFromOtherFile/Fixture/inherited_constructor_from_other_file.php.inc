<?php

namespace Rector\Tests\Issues\InheritedConstructorFromOtherFile\Fixture;

use Rector\Tests\Issues\InheritedConstructorFromOtherFile\Source\SomeParentWithDeepConstructor;

final class ChildWithoutConstructor extends SomeParentWithDeepConstructor
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\InheritedConstructorFromOtherFile\Fixture;

use Rector\Tests\Issues\InheritedConstructorFromOtherFile\Source\SomeParentWithDeepConstructor;

final class ChildWithoutConstructor extends SomeParentWithDeepConstructor
{
    public function __construct(protected string $name, private readonly \Rector\Tests\Issues\InheritedConstructorFromOtherFile\Source\SomeService $someService)
    {
        parent::__construct($name);
    }
}

?>
