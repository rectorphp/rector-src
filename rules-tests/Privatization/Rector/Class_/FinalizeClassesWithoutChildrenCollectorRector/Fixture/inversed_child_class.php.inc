<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenCollectorRector\Fixture;

class InversedChildClass extends InversedIsParentClass
{
}

class InversedIsParentClass
{
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenCollectorRector\Fixture;

final class InversedChildClass extends InversedIsParentClass
{
}

class InversedIsParentClass
{
}

?>
