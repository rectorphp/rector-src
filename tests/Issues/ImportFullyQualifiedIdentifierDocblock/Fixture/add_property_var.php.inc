<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock;

final class AddPropertyVar
{
    public $property;
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock;

use DateTime;
final class AddPropertyVar
{
    /**
     * @var DateTime
     */
    public $property;
}

?>
