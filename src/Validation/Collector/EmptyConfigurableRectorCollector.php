<?php

declare(strict_types=1);

namespace Rector\Core\Validation\Collector;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\NonPhpFile\Rector\RenameClassNonPhpRector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @see \Rector\Core\Tests\Validation\Collector\EmptyConfigurableRectorCollector\EmptyConfigurableRectorCollectorTest
 */
final class EmptyConfigurableRectorCollector
{
    /**
     * These are rector rules that the default values config are not array
     * For
     *
     * @var array<class-string<RectorInterface>>
     */
    private const ALLOWED_RULES_FALLBACK_DEFAULT_CONFIG = [
        'Rector\Php74\Rector\Property\TypedPropertyRector',
        'Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector',
        'Rector\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector',
        'Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector',
    ];

    public function __construct(
        private readonly ContainerBuilder $containerBuilder
    ) {
    }

    /**
     * @return array<class-string<ConfigurableRectorInterface>>
     */
    public function resolveEmptyConfigurableRectorClasses(): array
    {
        $emptyConfigurableRectorClasses = [];

        foreach ($this->containerBuilder->getServiceIds() as $serviceId) {
            if (! is_a($serviceId, ConfigurableRectorInterface::class, true)) {
                continue;
            }

            // it seems always loaded
            if (is_a($serviceId, RenameClassNonPhpRector::class, true)) {
                continue;
            }

            $serviceDefinition = $this->containerBuilder->getDefinition($serviceId);
            if ($this->hasConfigureMethodCall($serviceDefinition)) {
                continue;
            }

            if (in_array($serviceId, self::ALLOWED_RULES_FALLBACK_DEFAULT_CONFIG, true)) {
                continue;
            }

            $emptyConfigurableRectorClasses[] = $serviceId;
        }

        return $emptyConfigurableRectorClasses;
    }

    private function hasConfigureMethodCall(Definition $definition): bool
    {
        foreach ($definition->getMethodCalls() as $methodCall) {
            if ($methodCall[0] === 'configure') {
                if (! isset($methodCall[1][0])) {
                    return false;
                }

                return $methodCall[1][0] !== [];
            }
        }

        return false;
    }
}
