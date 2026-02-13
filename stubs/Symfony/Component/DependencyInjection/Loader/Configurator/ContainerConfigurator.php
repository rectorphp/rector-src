<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use Symfony\Component\ExpressionLanguage\Expression;

if (class_exists('Symfony\\Component\\DependencyInjection\\Loader\\Configurator\\ContainerConfigurator')) {
    return;
}

class ContainerConfigurator
{
    public function services(): ServiceConfigurator
    {
        return new ServiceConfigurator();
    }
}
