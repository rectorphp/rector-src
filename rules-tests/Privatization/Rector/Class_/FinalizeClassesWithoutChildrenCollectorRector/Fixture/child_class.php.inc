<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenCollectorRector\Fixture;

class IsParentClass
{
}

class ChildClass extends IsParentClass
{
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenCollectorRector\Fixture;

class IsParentClass
{
}

final class ChildClass extends IsParentClass
{
}

?>
