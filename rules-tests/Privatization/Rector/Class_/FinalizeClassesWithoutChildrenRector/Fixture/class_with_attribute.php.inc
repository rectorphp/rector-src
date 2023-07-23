<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]
class ClassWithAttribute
{
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]
final class ClassWithAttribute
{
}

?>
