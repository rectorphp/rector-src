<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SelfMethodReference
{
    public function run()
    {
        return [
            'contentTemplate' => 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SelfMethodReference::indexAction',
            'withSlash' => '\Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SelfMethodReference::indexAction',
        ];
    }

    public function indexAction()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SelfMethodReference
{
    public function run()
    {
        return [
            'contentTemplate' => self::class . '::indexAction',
            'withSlash' => self::class . '::indexAction',
        ];
    }

    public function indexAction()
    {
    }
}

?>
