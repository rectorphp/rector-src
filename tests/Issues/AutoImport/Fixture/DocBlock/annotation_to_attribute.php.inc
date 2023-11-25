<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

/**
 * @\Doctrine\ORM\Mapping\Entity()
 */
class SomeEntity
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

use Doctrine\ORM\Mapping\Entity;
#[Entity]
class SomeEntity
{
}

?>