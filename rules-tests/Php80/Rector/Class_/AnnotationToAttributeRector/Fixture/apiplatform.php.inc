<?php

/**
 * @ApiPlatform\Core\Annotation\ApiResource(
 *     routePrefix="/demo/"
 * )
 */
final class DemoFile
{
}
?>
-----
<?php

#[\ApiPlatform\Core\Annotation\ApiResource(routePrefix: '/demo/')]
final class DemoFile
{
}
?>
