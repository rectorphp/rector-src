<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class ChangeBareProperty
{
    private ?\PDO $_pdo;
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class ChangeBareProperty
{
    private ?\PDO $pdo;
}

?>
