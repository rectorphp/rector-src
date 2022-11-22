<?php

declare(strict_types=1);

namespace Rector\Core\Console\Command;

use Nette\Utils\Json;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Configuration\Option;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\ComplementaryRectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ListRulesCommand extends Command
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private readonly RectorOutputStyle $rectorOutputStyle,
        private readonly array $rectors
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('list-rules');
        $this->setDescription('Show loaded Rectors');

        $this->addOption(
            Option::OUTPUT_FORMAT,
            null,
            InputOption::VALUE_REQUIRED,
            'Select output format',
            ConsoleOutputFormatter::NAME
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rectorClasses = $this->resolveRectorClasses();

        $outputFormat = $input->getOption(Option::OUTPUT_FORMAT);
        if ($outputFormat === 'json') {
            $data = [
                'rectors' => $rectorClasses,
            ];

            echo Json::encode($data, Json::PRETTY);
            return Command::SUCCESS;
        }

        $this->rectorOutputStyle->title('Loaded Rector rules');
        $this->rectorOutputStyle->listing($rectorClasses);

        return Command::SUCCESS;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    private function resolveRectorClasses(): array
    {
        $customRectors = array_filter(
            $this->rectors,
            static function (RectorInterface $rector): bool {
                if ($rector instanceof PostRectorInterface) {
                    return false;
                }

                return ! $rector instanceof ComplementaryRectorInterface;
            }
        );

        $rectorClasses = array_map(static fn (RectorInterface $rector): string => $rector::class, $customRectors);
        sort($rectorClasses);

        return $rectorClasses;
    }
}
