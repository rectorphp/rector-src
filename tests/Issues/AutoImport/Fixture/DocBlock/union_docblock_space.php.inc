<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use DateTime;

class UnionDocblockSpace
{
    /**
     * @param array<(DateTime|null)> $param
     */
    public function some($param)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use DateTimeInterface;

class UnionDocblockSpace
{
    /**
     * @param array<(DateTimeInterface|null)> $param
     */
    public function some($param)
    {
    }
}

?>
