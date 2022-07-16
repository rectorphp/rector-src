<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection\CompilerPass;

use Rector\Core\Configuration\Option;
use Rector\Core\Contract\Rector\RectorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This compiler pass removed Rectors skipped in `SKIP` parameters.
 * It uses Skipper from Symplify - https://github.com/symplify/skipper
 */
final class RemoveSkippedRectorsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $skippedRectorClasses = $this->resolveSkippedRectorClasses($containerBuilder);

        foreach ($containerBuilder->getDefinitions() as $id => $definition) {
            if ($definition->getClass() === null) {
                continue;
            }

            if (! in_array($definition->getClass(), $skippedRectorClasses, true)) {
                continue;
            }

            $containerBuilder->removeDefinition($id);
        }
    }

    /**
     * @return string[]
     */
    private function resolveSkippedRectorClasses(ContainerBuilder $containerBuilder): array
    {
        $skipParameters = (array) $containerBuilder->getParameter(Option::SKIP);

        return array_filter($skipParameters, fn ($element): bool => $this->isRectorClass($element));
    }

    private function isRectorClass(mixed $element): bool
    {
        if (! is_string($element)) {
            return false;
        }

        return is_a($element, RectorInterface::class, true);
    }
}
