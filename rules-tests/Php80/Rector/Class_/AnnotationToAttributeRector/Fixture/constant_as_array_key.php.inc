<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get" = {
 *             "normalization_context" =  {
 *                 AbstractObjectNormalizer::SKIP_NULL_VALUES = true
 *             }
 *         }
 *     }
 * )
 */
final class DemoClass {
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

#[ApiResource(
    itemOperations: [
        "get" => [
            "normalization_context" => [
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        ],
    ]
)]
final class DemoClass
{
}
?>
