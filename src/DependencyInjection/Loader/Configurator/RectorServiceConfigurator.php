<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ServiceConfigurator;

/**
 * Same as Symfony service configurator, with extra "configure()" method for easier DX
 */
final class RectorServiceConfigurator extends ServiceConfigurator
{

}
