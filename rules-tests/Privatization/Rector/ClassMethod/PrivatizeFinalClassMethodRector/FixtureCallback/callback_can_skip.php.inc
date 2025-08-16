<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

final class FinalClassWithProtectedMethods
{
    protected function someMethod() {}

    protected function shouldBeProtected() {}
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

final class FinalClassWithProtectedMethods
{
    private function someMethod() {}

    protected function shouldBeProtected() {}
}

?>
