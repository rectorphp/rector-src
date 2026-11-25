<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SelfMethodReferenceConcat
{
    public function run(string $func)
    {
        return 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SelfMethodReferenceConcat::' . $func . 'Action';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SelfMethodReferenceConcat
{
    public function run(string $func)
    {
        return self::class . '::' . $func . 'Action';
    }
}

?>
