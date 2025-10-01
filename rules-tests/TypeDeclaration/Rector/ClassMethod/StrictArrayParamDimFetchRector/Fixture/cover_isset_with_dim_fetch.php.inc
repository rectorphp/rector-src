<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class CoverIssetWithDimFetch
{
    public function run($param, $item): bool
    {
        if (! isset($param[$item])) {
            return false;
        }

        if ($param[$item] === 100) {
            return true;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class CoverIssetWithDimFetch
{
    public function run(array $param, $item): bool
    {
        if (! isset($param[$item])) {
            return false;
        }

        if ($param[$item] === 100) {
            return true;
        }

        return false;
    }
}

?>
