<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class Fixture
{
    public function process($key)
    {
        $items = [
            'first' => 'Firstitem',
            'second' => 'Seconditem',
        ];

        return $items[$key];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class Fixture
{
    public function process(string $key)
    {
        $items = [
            'first' => 'Firstitem',
            'second' => 'Seconditem',
        ];

        return $items[$key];
    }
}

?>
