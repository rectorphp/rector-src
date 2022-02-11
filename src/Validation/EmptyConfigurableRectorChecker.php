<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use Rector\Core\Contract\Console\OutputStyleInterface;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Validation\Collector\EmptyConfigurableRectorCollector;

final class EmptyConfigurableRectorChecker
{
    public function __construct(
        private readonly EmptyConfigurableRectorCollector $emptyConfigurableRectorCollector,
        private readonly OutputStyleInterface $rectorOutputStyle
    ) {
    }

    public function check(): void
    {
        $emptyConfigurableRectorClasses = $this->emptyConfigurableRectorCollector->resolveEmptyConfigurableRectorClasses();
        if ($emptyConfigurableRectorClasses === []) {
            return;
        }

        $this->reportWarningMessage($emptyConfigurableRectorClasses);

        $solutionMessage = sprintf(
            'Do you want to run them?%sConfigure them in `rector.php` with "...->configure(...);"',
            PHP_EOL
        );
        $this->rectorOutputStyle->note($solutionMessage);

        if (! $this->rectorOutputStyle->isVerbose()) {
            // ensure there is new line after progress bar and report : "[OK] Rector is done!" with add a space
            $this->rectorOutputStyle->writeln(' ');
        }
    }

    /**
     * @param array<class-string<ConfigurableRectorInterface>> $emptyConfigurableRectorClasses
     */
    private function reportWarningMessage(array $emptyConfigurableRectorClasses): void
    {
        $warningMessage = sprintf(
            'Your project contains %d configurable rector rules that are skipped as need to be configured to run.',
            count($emptyConfigurableRectorClasses)
        );
        $this->rectorOutputStyle->warning($warningMessage);

        foreach ($emptyConfigurableRectorClasses as $emptyConfigurableRectorClass) {
            $this->rectorOutputStyle->writeln(' * ' . $emptyConfigurableRectorClass);
        }

        // to take time to absorb it
        sleep(3);
    }
}
