<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use OndraM\CiDetector\CiDetector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use function sprintf;

final class SetupCICommand extends Command
{
    /**
     * @var string
     * @see https://regex101.com/r/etcmog/2
     */
    private const GITHUB_REPOSITORY_REGEX = '#github\.com[:\/](?<repository_name>.*?)\.git#';

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
            $noteMessage = sprintf(
                'Only Github Actions and GitLab are supported for now.%s Contribute your CI to Rector to make this work: %s',
                PHP_EOL,
                'https://github.com/rectorphp/rector-src/'
            );

            $this->symfonyStyle->note($noteMessage);
            return self::SUCCESS;
        }

        $rectorWorkflowFilePath = getcwd() . '/.github/workflows/rector.yaml';
        if (file_exists($rectorWorkflowFilePath)) {
            $response = $this->symfonyStyle->ask('The "rector.yaml" workflow already exists. Overwrite it?', 'Yes');
            if (! in_array($response, ['y', 'yes', 'Yes'], true)) {
                $this->symfonyStyle->note('Nothing changed');
                return self::SUCCESS;
            }
        }

        $currentRepository = $this->resolveCurrentRepositoryName(getcwd());
        if ($currentRepository === null) {
            $this->symfonyStyle->error('Current repository name could not be resolved');

            return self::FAILURE;
        }

        if ($ci === CiDetector::CI_GITLAB) {
            // add snippet in the end of file or include it?
        }

        if ($ci === CiDetector::CI_GITHUB_ACTIONS) {
            $this->addGithubActionsWorkflow($currentRepository, $rectorWorkflowFilePath);
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

        if (file_exists(getcwd() . '/.gitlab-ci.yml')) {
            return CiDetector::CI_GITLAB;
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

    private function addGithubActionsWorkflow(string $currentRepository, string $rectorWorkflowFilePath): void
    {
        $workflowTemplate = FileSystem::read(__DIR__ . '/../../../templates/rector-github-action-check.yaml');
        $workflowContents = strtr($workflowTemplate, [
            '__CURRENT_REPOSITORY__' => $currentRepository,
        ]);

        FileSystem::write($rectorWorkflowFilePath, $workflowContents);

        $this->symfonyStyle->newLine();
        $this->symfonyStyle->success('The ".github/workflows/rector.yaml" file was added');

        $this->symfonyStyle->writeln('<comment>2 more steps to run Rector in CI:</comment>');

        $this->symfonyStyle->newLine();
        $this->symfonyStyle->writeln(
            '1) Generate token with "repo" scope:' . \PHP_EOL . 'https://github.com/settings/tokens/new'
        );

        $this->symfonyStyle->newLine();

        $repositoryNewSecretsLink = sprintf('https://github.com/%s/settings/secrets/actions/new', $currentRepository);
        $this->symfonyStyle->writeln(
            '2) Add the token to Action secrets as "ACCESS_TOKEN":' . \PHP_EOL . $repositoryNewSecretsLink
        );
    }
}
