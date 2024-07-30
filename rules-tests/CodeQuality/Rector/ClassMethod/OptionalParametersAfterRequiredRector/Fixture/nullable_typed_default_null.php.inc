<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

/**
 * @see https://3v4l.org/leYmD#v8.1.19
 */
final class NullableTypedDefaultNull
{
    public function run(?string $a = null, $required)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

/**
 * @see https://3v4l.org/leYmD#v8.1.19
 */
final class NullableTypedDefaultNull
{
    public function run($required, ?string $a = null)
    {
    }
}

?>
