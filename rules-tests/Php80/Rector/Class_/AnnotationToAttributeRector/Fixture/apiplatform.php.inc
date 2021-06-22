<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

/**
 * @ApiPlatform\Core\Annotation\ApiResource(
 *     routePrefix="/demo/",
 * )
 */
final class DemoFile
{
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

#[\ApiPlatform\Core\Annotation\ApiResource(routePrefix: '/demo/')]
final class DemoFile
{
}
?>
