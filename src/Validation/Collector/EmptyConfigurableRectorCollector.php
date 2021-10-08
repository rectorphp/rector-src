<?php

declare(strict_types=1);

namespace Rector\Core\Validation\Collector;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\NonPhpFile\Rector\RenameClassNonPhpRector;
use Rector\Naming\Naming\PropertyNaming;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

class EmptyConfigurableRectorCollector
{
    public function __construct(
        private PrivatesAccessor $privatesAccessor,
        private PropertyNaming $propertyNaming
    ) {
    }

    /**
     * @param RectorInterface[] $rectors
     * @return RectorInterface[]
     */
    public function resolveEmptyConfigurable(array $rectors): array
    {
        $emptyConfigurableRectors = [];
        foreach ($rectors as $rector) {
            if ($this->shouldSkip($rector)) {
                continue;
            }

            $ruleDefinition = $rector->getRuleDefinition();

            /** @var CodeSampleInterface[] $codeSamples */
            $codeSamples = $ruleDefinition->getCodeSamples();
            foreach ($codeSamples as $codeSample) {
                $configuration = $codeSample->getConfiguration();
                if (! is_array($configuration)) {
                    continue;
                }

                foreach ($configuration as $key => $config) {
                    if (! is_array($config)) {
                        continue;
                    }

                    $key = $this->propertyNaming->underscoreToName($key);
                    if (! property_exists($rector, $key)) {
                        continue;
                    }

                    $value = $this->privatesAccessor->getPrivateProperty($rector, $key);
                    if (is_array($value) && $value === []) {
                        $emptyConfigurableRectors[] = $rector;
                        continue 3;
                    }
                }
            }
        }

        return $emptyConfigurableRectors;
    }

    private function shouldSkip(RectorInterface $rector): bool
    {
        if (! $rector instanceof ConfigurableRectorInterface) {
            return true;
        }

        // it seems always loaded
        return $rector instanceof RenameClassNonPhpRector;
    }
}