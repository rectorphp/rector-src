<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Field;

final class FullyQualifiedStillImported
{
    public function run(): Field\TextField
    {
        new \Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Query\Parameter();

        return new Field\TextField();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Query\Parameter;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Field;

final class FullyQualifiedStillImported
{
    public function run(): Field\TextField
    {
        new Parameter();

        return new Field\TextField();
    }
}

?>
