<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

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

final class NullableTypedDefaultNull
{
    public function run($required, ?string $a = null)
    {
    }
}

?>
