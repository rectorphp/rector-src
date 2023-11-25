<?php

namespace Rector\Core\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

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

namespace Rector\Core\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping;

#[Mapping\Entity]
class SubNamespaceFromUse
{
}

?>