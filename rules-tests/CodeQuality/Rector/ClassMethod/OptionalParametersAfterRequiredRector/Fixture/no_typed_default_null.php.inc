<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

/**
 * @see https://3v4l.org/5Ek8A#v8.0.30
 */
final class NoTypedDefaultNull
{
    public function run($a = null, $required)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

/**
 * @see https://3v4l.org/5Ek8A#v8.0.30
 */
final class NoTypedDefaultNull
{
    public function run($required, $a = null)
    {
    }
}

?>
