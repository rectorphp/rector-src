<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

/**
 * @\Doctrine\ORM\Mapping\Entity()
 */
class SomeEntity
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use Doctrine\ORM\Mapping\Entity;
#[Entity]
class SomeEntity
{
}

?>
