<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use Rector\Tests\Issues\AutoImport\Source\Docblock\DateTime;

class RenameConflictDocblockName
{
    /**
     * @param \DateTime|DateTime $dateTime
     */
    public function some($dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use DateTimeInterface;
use Rector\Tests\Issues\AutoImport\Source\Docblock\DateTime;

class RenameConflictDocblockName
{
    /**
     * @param DateTimeInterface|DateTime $dateTime
     */
    public function some($dateTime)
    {
    }
}

?>
