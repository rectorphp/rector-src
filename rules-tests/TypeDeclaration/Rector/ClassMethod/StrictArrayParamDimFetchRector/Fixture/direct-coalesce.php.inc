<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class DirectCoalesce
{
    public function run($productOptions)
    {
        return $productOptions['list'] ?? 'default';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class DirectCoalesce
{
    public function run(array $productOptions)
    {
        return $productOptions['list'] ?? 'default';
    }
}

?>
