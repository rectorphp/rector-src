<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock;

use stdClass;

final class AddPropertyVar2
{
    public $property;
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock;

use DateTime;
use stdClass;

final class AddPropertyVar2
{
    /**
     * @var DateTime
     */
    public $property;
}

?>
