<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\FixtureAutoImport;

use Closure;

class DoNotImportIterable
{
    /**
     * @param iterable|int $baz
     */
    public function bar($baz)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\FixtureAutoImport;

use Closure;

class DoNotImportIterable
{
    public function bar(iterable|int $baz)
    {
    }
}

?>
