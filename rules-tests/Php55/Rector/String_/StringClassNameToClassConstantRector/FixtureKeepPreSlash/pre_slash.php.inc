<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\FixtureKeepPreSlash;

final class PreSlash
{
    public function preSlash()
    {
        return '\Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\FixtureKeepPreSlash;

final class PreSlash
{
    public function preSlash()
    {
        return '\\' . \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::class;
    }
}

?>
