<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock;

final class AddPropertyVar3
{
    public $property;
}

$x = new \stdClass;

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock;

use stdClass;
use DateTime;

final class AddPropertyVar3
{
    /**
     * @var DateTime
     */
    public $property;
}

$x = new stdClass;

?>
