<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTraitWithAbstractMethod;

// @see https://3v4l.org/X2LNT
final class OverrideAbstractFromTrait
{
    use ExampleFromTraitWithAbstractMethod;

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

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTraitWithAbstractMethod;

// @see https://3v4l.org/X2LNT
final class OverrideAbstractFromTrait
{
    use ExampleFromTraitWithAbstractMethod;

    #[\Override]
    public function run()
    {
        return 'hello';
    }
}

?>
