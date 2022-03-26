<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class KeyWithColon
{
    public function run(string $name)
    {
        return ['prefix:key' => $name];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class KeyWithColon
{
    /**
     * @return array{"prefix:key": string}
     */
    public function run(string $name)
    {
        return ['prefix:key' => $name];
    }
}

?>
