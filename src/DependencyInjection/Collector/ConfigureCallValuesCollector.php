<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection\Collector;

use Rector\Core\Console\Style\SymfonyStyleFactory;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Util\ArrayParametersMerger;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use ReflectionClass;
use ReflectionClassConstant;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Definition;

final class ConfigureCallValuesCollector
{
    /**
     * @var array<string, mixed[]>
     */
    private array $configureCallValuesByRectorClass = [];

    private readonly ArrayParametersMerger $arrayParametersMerger;

    private readonly SymfonyStyle $symfonyStyle;

    public function __construct()
    {
        $this->arrayParametersMerger = new ArrayParametersMerger();
        $symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
        $this->symfonyStyle = $symfonyStyleFactory->create();
    }

    /**
     * @return mixed[]
     */
    public function getConfigureCallValues(string $rectorClass): array
    {
        return $this->configureCallValuesByRectorClass[$rectorClass] ?? [];
    }

    /**
     * @param class-string<ConfigurableRectorInterface> $className
     */
    public function collectFromServiceAndClassName(string $className, Definition $definition): void
    {
        foreach ($definition->getMethodCalls() as $methodCall) {
            if ($methodCall[0] !== 'configure') {
                continue;
            }

            $this->addConfigureCallValues($className, $methodCall[1]);
        }
    }

    /**
     * @param class-string<ConfigurableRectorInterface> $rectorClass
     * @param mixed[] $configureValues
     */
    private function addConfigureCallValues(string $rectorClass, array $configureValues): void
    {
        foreach ($configureValues as $configureValue) {
            // is nested or unnested value?
            if (is_array($configureValue) && count($configureValue) === 1) {
                $firstKey = array_key_first($configureValue);

                if (is_string($firstKey) && is_array($configureValue[$firstKey])) {
                    // has class some public constants?
                    // fixes bug when 1 item is unwrapped and treated as constant key, without rule having public constant
                    $classReflection = new ReflectionClass($rectorClass);

                    $constantNamesToValues = $classReflection->getConstants(ReflectionClassConstant::IS_PUBLIC);
                    foreach ($constantNamesToValues as $constantName => $constantValue) {
                        if ($constantValue === $firstKey) {
                            $reflectionConstant = $classReflection->getReflectionConstant($constantName);
                            if ($reflectionConstant === false) {
                                continue;
                            }

                            if (! str_contains((string) $reflectionConstant->getDocComment(), '@deprecated')) {
                                continue;
                            }

                            $warningMessage = sprintf(
                                'The constant for "%s::%s" is deprecated.%sUse "$rectorConfig->ruleWithConfiguration()" instead.',
                                $rectorClass,
                                $constantName,
                                PHP_EOL
                            );
                            $this->symfonyStyle->warning($warningMessage);

                            $configureValue = $configureValue[$firstKey];
                            break;
                        }
                    }
                }
            }

            if (! isset($this->configureCallValuesByRectorClass[$rectorClass])) {
                $this->configureCallValuesByRectorClass[$rectorClass] = $configureValue;
            } else {
                $mergedParameters = $this->arrayParametersMerger->merge(
                    $this->configureCallValuesByRectorClass[$rectorClass],
                    $configureValue
                );

                $this->configureCallValuesByRectorClass[$rectorClass] = $mergedParameters;
            }
        }
    }
}
