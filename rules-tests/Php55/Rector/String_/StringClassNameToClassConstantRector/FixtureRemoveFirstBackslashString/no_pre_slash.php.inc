<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\FixtureRemoveFirstBackslashString;

final class NoPreSlash
{
    public function preSlash()
    {
        return '\Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\FixtureRemoveFirstBackslashString;

final class NoPreSlash
{
    public function preSlash()
    {
        return \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::class;
    }
}

?>
