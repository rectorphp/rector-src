<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTraitAbstract;

// @see https://3v4l.org/X2LNT
final class OverrideAbstractFromTrait
{
    use ExampleFromTraitAbstract;

    public function run()
    {
        return 'hello';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTraitAbstract;

// @see https://3v4l.org/X2LNT
final class OverrideAbstractFromTrait
{
    use ExampleFromTraitAbstract;

    #[\Override]
    public function run()
    {
        return 'hello';
    }
}

?>
