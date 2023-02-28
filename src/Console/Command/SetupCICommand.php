<?php

declare(strict_types=1);

namespace Rector\Core\Console\Command;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use OndraM\CiDetector\CiDetector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

final class SetupCICommand extends Command
{
    /**
     * @var string
     * @see https://regex101.com/r/etcmog/1
     */
    private const GITHUB_REPOSITORY_REGEX = '#github\.com:(?<repository_name>.*?)\.git#';

    public function __construct(
        private readonly SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('setup-ci');
        $this->setDescription('Add CI workflow to let Rector work for you');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // detect current CI
        $ci = $this->resolveCurrentCI();
        if ($ci === null) {
            $this->symfonyStyle->error('No CI detected');
            return self::FAILURE;
        }

        if ($ci === CiDetector::CI_GITHUB_ACTIONS) {
            $rectorWorkflowFilePath = getcwd() . '/.github/workflows/rector.yaml';
            if (file_exists($rectorWorkflowFilePath)) {
                $this->symfonyStyle->warning('The "rector.yaml" workflow already exists');
                return self::SUCCESS;
            }

            $currentRepository = $this->resolveCurrentRepositoryName(getcwd());
            if ($currentRepository === null) {
                $this->symfonyStyle->error('Current repository name could not be resolved');

                return self::FAILURE;
            }

            $workflowTemplate = FileSystem::read(__DIR__ . '/../../../templates/rector-github-action-check.yaml');

            $workflowContents = strtr($workflowTemplate, [
                '__CURRENT_REPOSITORY__' => $currentRepository,
            ]);

            FileSystem::write($rectorWorkflowFilePath, $workflowContents);
            $this->symfonyStyle->success('The "rector.yaml" workflow was added');
        }

        return Command::SUCCESS;
    }

    /**
     * @return CiDetector::CI_*|null
     */
    private function resolveCurrentCI(): ?string
    {
        if (file_exists(getcwd() . '/.github')) {
            return CiDetector::CI_GITHUB_ACTIONS;
        }

        return null;
    }

    private function resolveCurrentRepositoryName(string $currentDirectory): ?string
    {
        // resolve current repository name
        $process = new Process(['git', 'remote', 'get-url', 'origin'], $currentDirectory, null, null, null);
        $process->run();

        $output = $process->getOutput();

        $match = Strings::match($output, self::GITHUB_REPOSITORY_REGEX);
        return $match['repository_name'] ?? null;
    }
}
