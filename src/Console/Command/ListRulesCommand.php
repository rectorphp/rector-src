<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Entropy\Console\Contract\CommandInterface;
use Nette\Utils\Json;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Console\ExitCode;
use Rector\Console\Rule\AllRectorRulesProvider;
use Rector\Console\Style\RectorStyle;
use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\Skipper\Skipper\Skipper;

final readonly class ListRulesCommand implements CommandInterface
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private RectorStyle $rectorStyle,
        private Skipper $skipper,
        private AllRectorRulesProvider $allRectorRulesProvider,
        private array $rectors
    ) {
    }

    public function getName(): string
    {
        return 'list-rules';
    }

    public function getDescription(): string
    {
        return 'Show loaded Rectors';
    }

    /**
     * @param bool   $all          List all existing rules in core and rector-* packages, not only the loaded ones
     * @param string $outputFormat Select output format
     *
     * @option $outputFormat
     *
     * @api invoked via reflection by the Entropy console application
     *
     * @return ExitCode::*
     */
    public function run(bool $all = false, string $outputFormat = ConsoleOutputFormatter::NAME): int
    {
        $rectorClasses = $all
            ? $this->allRectorRulesProvider->provide()
            : $this->resolveRectorClasses();

        $skippedClasses = $this->getSkippedRectorClasses();

        if ($outputFormat === 'json') {
            $data = [
                'rectors' => $rectorClasses,
                'skipped-rectors' => $skippedClasses,
            ];

            echo Json::encode($data, pretty: true) . PHP_EOL;
            return ExitCode::SUCCESS;
        }

        $this->rectorStyle->title('Loaded Rector rules');
        $this->rectorStyle->listing($rectorClasses);

        if ($skippedClasses !== []) {
            $this->rectorStyle->title('Skipped Rector rules');
            $this->rectorStyle->listing($skippedClasses);
        }

        $this->rectorStyle->newLine();
        $this->rectorStyle->note(sprintf('Loaded %d rules', count($rectorClasses)));

        return ExitCode::SUCCESS;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    private function resolveRectorClasses(): array
    {
        $customRectors = array_filter(
            $this->rectors,
            static fn (RectorInterface $rector): bool => ! $rector instanceof PostRectorInterface
        );

        $rectorClasses = array_map(static fn (RectorInterface $rector): string => $rector::class, $customRectors);
        sort($rectorClasses);

        return array_unique($rectorClasses);
    }

    /**
     * @return array<class-string>
     */
    private function getSkippedRectorClasses(): array
    {
        $skippedRectorClasses = [];

        foreach ($this->skipper->resolveSkippedClasses() as $rectorClass => $fileList) {
            // ignore specific skips
            if ($fileList !== null) {
                continue;
            }

            $skippedRectorClasses[] = $rectorClass;
        }

        return $skippedRectorClasses;
    }
}
