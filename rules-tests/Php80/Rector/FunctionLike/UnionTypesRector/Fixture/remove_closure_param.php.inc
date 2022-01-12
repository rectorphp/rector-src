<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

$fcn =
    /** @param int|string $_ */
    static fn (int|string $_) : int => 0;

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

$fcn =
    static fn (int|string $_) : int => 0;

?>
