<?php

namespace Rector\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity()
 */
class SubNamespaceFromUse
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping;

#[Mapping\Entity]
class SubNamespaceFromUse
{
}

?>
