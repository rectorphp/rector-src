<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use Symfony\Component\ExpressionLanguage\Expression;

if (class_exists('Symfony\\Component\\DependencyInjection\\Loader\\Configurator\\ServiceConfigurator')) {
    return;
}

class ServiceConfigurator
{
    function factory(string|array|ReferenceConfigurator|Expression $factory): static
    {
        return $this;
    }
}
