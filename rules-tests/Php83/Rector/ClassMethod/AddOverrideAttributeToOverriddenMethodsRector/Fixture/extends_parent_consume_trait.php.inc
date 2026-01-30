<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ParentConsumeTrait;

class ExtendsParentConsumeTrait extends ParentConsumeTrait
{
    public function run()
    {
        echo 'override default';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ParentConsumeTrait;

class ExtendsParentConsumeTrait extends ParentConsumeTrait
{
    #[\Override]
    public function run()
    {
        echo 'override default';
    }
}

?>
