<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SelfMethodReferenceInClosure
{
    public function run()
    {
        return static fn (): string => 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SelfMethodReferenceInClosure::indexAction';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SelfMethodReferenceInClosure
{
    public function run()
    {
        return static fn (): string => self::class . '::indexAction';
    }
}

?>
