<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use Nette\Utils\Strings;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\NonPhpFile\Rector\RenameClassNonPhpRector;
use Rector\Naming\Naming\PropertyNaming;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Throwable;

final class EmptyConfigurableRectorChecker
{
    public function __construct(
        private PrivatesAccessor $privatesAccessor,
        private PropertyNaming $propertyNaming,
        private SymfonyStyle $symfonyStyle
    ) {
    }

    /**
     * @param RectorInterface[] $rectors
     */
    public function check(array $rectors): void
    {
        $emptyConfigurableRectors = $this->resolveEmptyConfigurable($rectors);

        if ($emptyConfigurableRectors === []) {
            return;
        }

        $this->reportWarningMessage($emptyConfigurableRectors);
        $this->reportEmptyConfigurableMessage($emptyConfigurableRectors);
    }

    /**
     * @param RectorInterface[] $rectors
     * @return RectorInterface[]
     */
    private function resolveEmptyConfigurable(array $rectors): array
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

                    try {
                        $key = $this->propertyNaming->underscoreToName($key);
                        $value = $this->privatesAccessor->getPrivateProperty($rector, $key);

                        if (is_array($value) && $value === []) {
                            $emptyConfigurableRectors[] = $rector;
                            continue 3;
                        }
                    } catch (Throwable) {
                        continue;
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

    /**
     * @param RectorInterface[] $emptyConfigurableRectors
     */
    private function reportWarningMessage(array $emptyConfigurableRectors): void
    {
        $warningMessage = sprintf(
            'Your project requires %d configurable rector rules that needs to be configured, use -vvv for detailed info.',
            count($emptyConfigurableRectors)
        );

        $this->symfonyStyle->warning($warningMessage);
    }

    /**
     * @param RectorInterface[] $emptyConfigurableRectors
     */
    private function reportEmptyConfigurableMessage(array $emptyConfigurableRectors): void
    {
        if (! $this->symfonyStyle->isVerbose()) {
            return;
        }

        foreach ($emptyConfigurableRectors as $emptyConfigurableRector) {
            $shortRectorClass = Strings::after($emptyConfigurableRector::class, '\\', -1);

            $rectorMessage = ' * ' . $shortRectorClass;
            $this->symfonyStyle->writeln($rectorMessage);
        }
    }
}
