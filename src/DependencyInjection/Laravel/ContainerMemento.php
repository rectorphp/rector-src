<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection\Laravel;

use Illuminate\Container\Container;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Util\Reflection\PrivatesAccessor;

/**
 * Helper service to modify Laravel container to remove skipped Rector rule
 */
final class ContainerMemento
{
    public static function forgetService(Container $container, string $rectorClass): void
    {
        // 1. remove the service
        $container->offsetUnset($rectorClass);

        // 2. remove tagged rule
        $privatesAccessor = new PrivatesAccessor();
        $privatesAccessor->propertyClosure($container, 'tags', static function (array $tags) use (
            $rectorClass
        ): array {
            foreach ($tags[RectorInterface::class] ?? [] as $key => $taggedClass) {
                if ($taggedClass === $rectorClass) {
                    unset($tags[RectorInterface::class][$key]);
                    break;
                }
            }

            return $tags;
        });
    }
}
