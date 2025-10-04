<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class IntOrString
{
    public function process($key)
    {
        $items = [
            'first' => 'Firstitem',
            111 => 'Seconditem',
            'second' => 'Seconditem',
        ];

        return $items[$key];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class IntOrString
{
    public function process(string|int $key)
    {
        $items = [
            'first' => 'Firstitem',
            111 => 'Seconditem',
            'second' => 'Seconditem',
        ];

        return $items[$key];
    }
}

?>
