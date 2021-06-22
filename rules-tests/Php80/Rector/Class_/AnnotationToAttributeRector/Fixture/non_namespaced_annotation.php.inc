<?php

/**
 * @ApiPlatform\Core\Annotation\ApiResource(
 *     routePrefix="/demo/"
 * )
 */
final class NonNamespacedAnnotation
{
}
?>
-----
<?php

#[\ApiPlatform\Core\Annotation\ApiResource(routePrefix: '/demo/')]
final class NonNamespacedAnnotation
{
}
?>
