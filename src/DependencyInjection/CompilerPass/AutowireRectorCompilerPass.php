<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection\CompilerPass;

use Rector\Core\Contract\Rector\RectorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AutowireRectorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $definitions = $containerBuilder->getDefinitions();

        foreach ($definitions as $definition) {
            if (! is_a((string) $definition->getClass(), RectorInterface::class, true)) {
                continue;
            }

            $definition->setAutowired(true);
        }
    }
}
