<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector\Fixture;

class NestedAssign
{
    private function someMethod(string $path): array
    {
        $stringLength = strlen($path);

        $items = [];
        $currentdItem = '';

        for ($i = 0; $i < $stringLength; $i++) {
            $currentdItem = (string) $path[$i];
        }

        if ($currentdItem !== '') {
            $items[] = $currentdItem;
        }

        return $items;
    }

}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector\Fixture;

class NestedAssign
{
    /**
     * @return string[]
     */
    private function someMethod(string $path): array
    {
        $stringLength = strlen($path);

        $items = [];
        $currentdItem = '';

        for ($i = 0; $i < $stringLength; $i++) {
            $currentdItem = (string) $path[$i];
        }

        if ($currentdItem !== '') {
            $items[] = $currentdItem;
        }

        return $items;
    }

}

?>
