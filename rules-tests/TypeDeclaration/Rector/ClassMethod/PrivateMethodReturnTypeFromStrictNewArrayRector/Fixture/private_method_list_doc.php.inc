<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector\Fixture;

final class PrivateMethodListDoc
{
    private function run()
    {
        $values = [];
        $values[] = 'item';

        return $values;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector\Fixture;

final class PrivateMethodListDoc
{
    /**
     * @return list<'item'>
     */
    private function run(): array
    {
        $values = [];
        $values[] = 'item';

        return $values;
    }
}

?>
