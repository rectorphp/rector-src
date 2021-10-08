<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use Nette\Utils\Strings;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\NonPhpFile\Rector\RenameClassNonPhpRector;
use Rector\Core\Validation\Collector\EmptyConfigurableRectorCollector;
use Rector\Naming\Naming\PropertyNaming;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

final class EmptyConfigurableRectorChecker
{
    public function __construct(
        private EmptyConfigurableRectorCollector $emptyConfigurableRectorCollector,
        private SymfonyStyle $symfonyStyle
    ) {
    }

    /**
     * @param RectorInterface[] $rectors
     */
    public function check(array $rectors): void
    {
        $emptyConfigurableRectors = $this->emptyConfigurableRectorCollector->resolveEmptyConfigurable($rectors);

        if ($emptyConfigurableRectors === []) {
            return;
        }

        $this->reportWarningMessage($emptyConfigurableRectors);
        $this->reportEmptyConfigurableMessage($emptyConfigurableRectors);
    }

    /**
     * @param RectorInterface[] $emptyConfigurableRectors
     */
    private function reportWarningMessage(array $emptyConfigurableRectors): void
    {
        $warningMessage = sprintf(
            'Your project contains %d configurable rector rules that skipped as need to be configured to run, use -vvv for detailed info.',
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
