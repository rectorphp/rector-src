<?php

declare(strict_types=1);

namespace Rector\Core\Validation\Collector;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\NonPhpFile\Rector\RenameClassNonPhpRector;
use Rector\Naming\Naming\PropertyNaming;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;

/**
 * @see \Rector\Core\Tests\Validation\Collector\EmptyConfigurableRectorCollector\EmptyConfigurableRectorCollectorTest
 */
final class EmptyConfigurableRectorCollector
{
    public function __construct(
        private readonly ContainerBuilder $containerBuilder,
        private readonly PrivatesAccessor $privatesAccessor,
        private readonly PropertyNaming $propertyNaming
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

            if ($this->shouldSkip($serviceId)) {
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

    /**
     * Skip the following config property conditions
     *    - start with exclude
     *    - not empty array default value
     *    - property not found by config, eg, on key value pairs in \Rector\Php74\Rector\Function_\ReservedFnFunctionRector
     *
     *          [
     *               'fn' => 'someFunctionName',
     *          ]
     *
     *      which there is no `fn` property in the rector class
     */
    private function shouldSkip(string $serviceId): bool
    {
        $rector = $this->containerBuilder->get($serviceId);
        $ruleDefinition = $rector->getRuleDefinition();

        /** @var ConfiguredCodeSample[] $codeSamples */
        $codeSamples = $ruleDefinition->getCodeSamples();
        foreach ($codeSamples as $codeSample) {
            $configuration = $codeSample->getConfiguration();

            $arrayKeys = array_keys($configuration);
            if ($arrayKeys === [0]) {
                return false;
            }

            foreach (array_keys($configuration) as $key) {
                if (! is_string($key)) {
                    return false;
                }

                $key = $this->propertyNaming->underscoreToName($key);
                if (! property_exists($rector, $key)) {
                    continue;
                }

                // @see https://github.com/rectorphp/rector-laravel/pull/19
                if (str_starts_with($key, 'exclude')) {
                    continue;
                }

                $value = $this->privatesAccessor->getPrivateProperty($rector, $key);
                if ($value === []) {
                    return false;
                }
            }
        }

        return true;
    }
}
