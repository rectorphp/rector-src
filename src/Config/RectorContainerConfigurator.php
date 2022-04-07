<?php

declare(strict_types=1);

namespace Rector\Core\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @api
 * Same as Symfony container configurator, with patched return type for "set()" method for easier DX.
 * It is an alias for internal class that is prefixed during build, so it's basically for keeping stable public API.
 */
final class RectorContainerConfigurator extends ContainerConfigurator
{
}
