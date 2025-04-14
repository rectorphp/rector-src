<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;
use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\SomeAbstractClass;

// @see https://3v4l.org/RrkgD
final class OverrideParentFromTrait extends SomeAbstractClass
{
    use ExampleFromTrait;

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

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;
use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\SomeAbstractClass;

// @see https://3v4l.org/RrkgD
final class OverrideParentFromTrait extends SomeAbstractClass
{
    use ExampleFromTrait;

    #[\Override]
    public function run()
    {
        return 'hello';
    }
}

?>
