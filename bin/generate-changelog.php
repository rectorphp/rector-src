<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> b44a70fd30 (fixup! misc)
use Httpful\Request;
use Nette\Utils\Strings;
=======
>>>>>>> 14a065d6df (fixup! fixup! misc)
use Rector\Utils\ChangelogGenerator\Changelog\ChangelogContentsFactory;
use Rector\Utils\ChangelogGenerator\GithubApiCaller;
use Rector\Utils\ChangelogGenerator\GitResolver;
<<<<<<< HEAD
>>>>>>> a873c36fa3 (fixup! fixup! misc)
=======
>>>>>>> b44a70fd30 (fixup! misc)
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(~E_DEPRECATED);

<<<<<<< HEAD
<<<<<<< HEAD
/**
 * Inspired from @see https://github.com/phpstan/phpstan-src/blob/master/bin/generate-changelog.php
 *
 * Usage:
 * GITHUB_TOKEN=<github_token> php bin/generate-changelog.php <from-commit> <to-commit> >> <file_to_dump.md>
 * GITHUB_TOKEN=ghp_... php bin/generate-changelog.php 07736c1 cb74bb6 >> CHANGELOG_dumped.md
 *
 * Generate the Composer token here: https://github.com/settings/tokens/new
 */
final class GenerateChangelogCommand extends Command
{
    /**
     * @var string
     */
    private const DEPLOY_REPOSITORY_NAME = 'rectorphp/rector';

    /**
     * @var string
     */
    private const DEVELOPMENT_REPOSITORY_NAME = 'rectorphp/rector-src';

    /**
     * @var string[]
     */
    private const EXCLUDED_THANKS_NAMES = ['TomasVotruba', 'samsonasik'];

    private GitResolver $gitResolver;

    public function __construct()
    {
        $this->gitResolver = new GitResolver();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('generate-changelog');
        $this->addArgument(Argument::FROM_COMMIT, InputArgument::REQUIRED);
        $this->addArgument(Argument::TO_COMMIT, InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fromCommit = (string) $input->getArgument(Argument::FROM_COMMIT);
        $toCommit = (string) $input->getArgument(Argument::TO_COMMIT);

        $commitLines = $this->gitResolver->resolveCommitLinesFromToHashes($fromCommit, $toCommit, $this);
        $commits = $this->mapCommitLinesToCommits($commitLines);

        $i = 0;

        $changelogLines = [];

        foreach ($commits as $commit) {
            $searchPullRequestsResponse = $this->searchPullRequests($commit, $output);

            $searchIssuesResponse = $this->searchIssues($commit, $output);

            $items = array_merge($searchPullRequestsResponse->items, $searchIssuesResponse->items);
            $parenthesis = 'https://github.com/' . self::DEVELOPMENT_REPOSITORY_NAME . '/commit/' . $commit->getHash();
            $thanks = null;
            $issuesToReference = [];

            foreach ($items as $item) {
                if (property_exists($item, 'pull_request') && $item->pull_request !== null) {
                    $parenthesis = sprintf(
                        '[#%d](%s)',
                        $item->number,
                        'https://github.com/' . self::DEVELOPMENT_REPOSITORY_NAME . '/pull/' . $item->number
                    );
                    $thanks = $item->user->login;
                    break;
                }

                $issuesToReference[] = sprintf('#%d', $item->number);
            }

            // clean commit from duplicating issue number
            $commitMatch = Strings::match($commit->getMessage(), '#(.*?)( \(\#\d+\))?$#ms');

            $commit = $commitMatch[1] ?? $commit->getMessage();

            $changelogLine = sprintf(
                '* %s (%s)%s%s',
                $commit,
                $parenthesis,
                $issuesToReference !== [] ? ', ' . implode(', ', $issuesToReference) : '',
                $this->createThanks($thanks)
            );

            $output->writeln($changelogLine);

            $changelogLines[] = $changelogLine;

            // not to throttle the GitHub API
            if ($i > 0 && $i % 8 === 0) {
                sleep(30);
            }

            ++$i;
        }

        // summarize into "Added Features" and "Bugfixes" groups

        return self::SUCCESS;
    }

    private function sendRequest(string $requestUri, OutputInterface $output): object
    {
        $response = Request::get($requestUri)
            ->sendsAndExpectsType('application/json')
            ->basicAuth('tomasvotruba', getenv('GITHUB_TOKEN'))
            ->send();

        if ($response->code !== 200) {
            $output->writeln(var_export($response->body, true));
            throw new InvalidArgumentException((string) $response->code);
        }

        return $response->body;
    }

    private function searchIssues(Commit $commit, OutputInterface $output): object
    {
        $requestUri = sprintf(
            'https://api.github.com/search/issues?q=repo:%s+%s',
            self::DEPLOY_REPOSITORY_NAME,
            $commit->getHash()
        );

        return $this->sendRequest($requestUri, $output);
    }

    private function searchPullRequests(Commit $commit, OutputInterface $output): object
    {
        $requestUri = sprintf(
            'https://api.github.com/search/issues?q=repo:%s+%s',
            self::DEVELOPMENT_REPOSITORY_NAME,
            $commit->getHash()
        );

        return $this->sendRequest($requestUri, $output);
    }

    /**
     * @param string[] $commitLines
     * @return Commit[]
     */
    private function mapCommitLinesToCommits(array $commitLines): array
    {
        return array_map(static function (string $line): Commit {
            [$hash, $message] = explode(' ', $line, 2);
            return new Commit($hash, $message);
        }, $commitLines);
    }

    private function createThanks(string|null $thanks): string
    {
        if ($thanks === null) {
            return '';
        }

        if (in_array($thanks, self::EXCLUDED_THANKS_NAMES, true)) {
            return '';
        }

        return sprintf(', Thanks @%s!', $thanks);
    }
}

final class GitResolver
{
    /**
     * @return string[]
     */
    public function resolveCommitLinesFromToHashes(string $fromCommit, string $toCommit): array
    {
        $commitHashRange = sprintf('%s..%s', $fromCommit, $toCommit);

        $output = $this->exec(['git', 'log', $commitHashRange, '--reverse', '--pretty=%H %s']);
        $commitLines = explode("\n", $output);

        // remove empty values
        return array_filter($commitLines);
    }

    /**
     * @param string[] $commandParts
     */
    private function exec(array $commandParts): string
    {
        $process = new Process($commandParts);
        $process->run();

        return $process->getOutput();
    }
}

final class Argument
{
    /**
     * @var string
     */
    public const FROM_COMMIT = 'from-commit';

    /**
     * @var string
     */
    public const TO_COMMIT = 'to-commit';
}

final class Commit
{
    public function __construct(
        private readonly string $hash,
        private readonly string $message
    ) {
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}

$generateChangelogCommand = new GenerateChangelogCommand();
=======
=======
>>>>>>> b44a70fd30 (fixup! misc)
$githubToken = getenv('GITHUB_TOKEN');
$githubApiCaller = new GithubApiCaller($githubToken);

$generateChangelogCommand = new GenerateChangelogCommand(
    new GitResolver(),
    $githubApiCaller,
    new ChangelogContentsFactory()
);
<<<<<<< HEAD
>>>>>>> a873c36fa3 (fixup! fixup! misc)
=======
>>>>>>> b44a70fd30 (fixup! misc)

$application = new Application();
$application->add($generateChangelogCommand);
$application->setDefaultCommand('generate-changelog', true);
$application->run();
