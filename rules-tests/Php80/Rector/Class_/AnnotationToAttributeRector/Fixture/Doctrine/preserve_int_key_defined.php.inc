<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\DiscriminatorMap({
 *     "cost" = "CostEntity",
 *     "product" = "ProductEntity",
 * })
 */
class PreserveIntKeyDefined
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\DiscriminatorMap(['cost' => 'CostEntity', 'product' => 'ProductEntity'])]
class PreserveIntKeyDefined
{
}

?>
