<?php

namespace Rector\Core\Tests\Issues\IssueReadonlyPropertyNullable\Fixture;

final class SomeClass
{
    private AnotherClass $anotherClass;

    public function run()
    {
        if ($this->anotherClass === null) {
            return null;
        }
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueReadonlyPropertyNullable\Fixture;

final class SomeClass
{
    private ?AnotherClass $anotherClass = null;

    public function run()
    {
        if ($this->anotherClass === null) {
            return null;
        }
    }
}

?>
