<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\FixtureAutoImport;

use stdClass;

class Fixture
{
    /**
     * @return stdClass|array
     */
    public function get()
    {
        if (random_int(0, 1) !== 0) {
            return new stdClass();
        }

        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\FixtureAutoImport;

use stdClass;

class Fixture
{
    public function get(): stdClass|array
    {
        if (random_int(0, 1) !== 0) {
            return new stdClass();
        }

        return [];
    }
}

?>
