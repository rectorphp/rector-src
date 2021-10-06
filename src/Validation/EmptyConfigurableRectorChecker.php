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
    public function __construct(private PrivatesAccessor $privatesAccessor, private PropertyNaming $propertyNaming, private SymfonyStyle $symfonyStyle)
    {
    }

    /**
     * @param RectorInterface[] $rectors
     */
    public function check(array $rectors): void
    {
        $emptyConfigurableRectors = [];
        foreach ($rectors as $rector) {
            if (! $rector instanceof ConfigurableRectorInterface) {
                continue;
            }

            // it seems always loaded
            if ($rector instanceof RenameClassNonPhpRector) {
                continue;
            }

            $ruleDefinition = $rector->getRuleDefinition();

            /** @var CodeSampleInterface[] $codeSamples */
            $codeSamples    = $ruleDefinition->getCodeSamples();
            foreach ($codeSamples as $codeSample) {
                $configuration = $codeSample->getConfiguration();
                foreach ($configuration as $key => $config) {
                    if (is_array($config)) {
                        try {
                            $key   = $this->propertyNaming->underscoreToName($key);
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
        }


        if ($emptyConfigurableRectors === []) {
            return;
        }

        $this->reportWarningMessage($emptyConfigurableRectors);
        $this->reportEmptyConfigurableMessage($emptyConfigurableRectors);
    }

    private function reportWarningMessage(array $emptyConfigurableRectors): void
    {
        $warningMessage = sprintf(
                'Your project requires %d configurable rector rules that needs to be configured, use -vvv for detailed info.',
                count($emptyConfigurableRectors)
        );

        $this->symfonyStyle->warning($warningMessage);
    }

    private function reportEmptyConfigurableMessage(array $emptyConfigurableRectors): void
    {
        if (! $this->symfonyStyle->isVerbose()) {
            return;
        }

        foreach ($emptyConfigurableRectors as $emptyConfigurableRector) {
            $shortRectorClass = Strings::after($emptyConfigurableRector::class, '\\', -1);

            $rectorMessage = sprintf(' * %s', $shortRectorClass);
            $this->symfonyStyle->writeln($rectorMessage);
        }
    }
}