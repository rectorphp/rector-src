<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector\Fixture;

#[SomeAttribute]
class ClassWithAttribute
{
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector\Fixture;

#[SomeAttribute]
final class ClassWithAttribute
{
}

?>
