<?php

declare(strict_types=1);

namespace Symfony\Component\HttpKernel\Bundle;

if (interface_exists('Symfony\Component\HttpKernel\Bundle\BundleInterface')) {
    return;
}

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface BundleInterface extends ContainerAwareInterface
{
    public function boot();
    public function shutdown();
    public function build(ContainerBuilder $container);
    public function getContainerExtension();
    public function getName();
    public function getNamespace();
    public function getPath();
}

