<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class OwnClassName
{
    public function run()
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnClassName',
            '\Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnClassName',
        ];
    }
}

trait OwnTraitName
{
    public function run()
    {
        return 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnTraitName';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class OwnClassName
{
    public function run()
    {
        return [
            self::class,
            self::class,
        ];
    }
}

trait OwnTraitName
{
    public function run()
    {
        return \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnTraitName::class;
    }
}

?>
