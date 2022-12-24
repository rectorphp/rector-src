<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator\Command;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Utils\ChangelogGenerator\Changelog\ChangelogContentsFactory;
use Rector\Utils\ChangelogGenerator\Enum\Option;
use Rector\Utils\ChangelogGenerator\Enum\RepositoryName;
use Rector\Utils\ChangelogGenerator\GithubApiCaller;
use Rector\Utils\ChangelogGenerator\GitResolver;
use Rector\Utils\ChangelogGenerator\ValueObject\Commit;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var string[]
     */
    private const EXCLUDED_THANKS_NAMES = ['TomasVotruba', 'samsonasik'];

    /**
     * @see https://regex101.com/r/jdT01W/1
     * @var string
     */
    private const ISSUE_NAME_REGEX = '#(.*?)( \(\#\d+\))?$#ms';

    public function __construct(
        private readonly GitResolver $gitResolver,
        private readonly GithubApiCaller $githubApiCaller,
        private readonly ChangelogContentsFactory $changelogContentsFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('generate-changelog');
        $this->addArgument(Option::FROM_COMMIT, InputArgument::REQUIRED);
        $this->addArgument(Option::TO_COMMIT, InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fromCommit = (string) $input->getArgument(Option::FROM_COMMIT);
        $toCommit = (string) $input->getArgument(Option::TO_COMMIT);

        $commits = $this->gitResolver->resolveCommitLinesFromToHashes($fromCommit, $toCommit);

        $i = 0;

        $changelogLines = [];

        foreach ($commits as $commit) {
            $searchPullRequestsResponse = $this->githubApiCaller->searchPullRequests($commit);
            $searchIssuesResponse = $this->githubApiCaller->searchIssues($commit);

            $items = array_merge($searchPullRequestsResponse->items, $searchIssuesResponse->items);
            $parenthesis = 'https://github.com/' . RepositoryName::DEVELOPMENT . '/commit/' . $commit->getHash();

            $thanks = null;
            $issuesToReference = [];

            foreach ($items as $item) {
                if (property_exists($item, 'pull_request') && $item->pull_request !== null) {
                    $parenthesis = sprintf(
                        '[#%d](%s)',
                        (int) $item->number,
                        'https://github.com/' . RepositoryName::DEVELOPMENT . '/pull/' . $item->number
                    );
                    $thanks = $item->user->login;
                    break;
                }

                $issuesToReference[] = '#' . $item->number;
            }

            // clean commit from duplicating issue number
            $commitMatch = Strings::match($commit->getMessage(), self::ISSUE_NAME_REGEX);

            $commit = $commitMatch[1] ?? $commit->getMessage();

            $changelogLine = sprintf(
                '* %s (%s)%s%s',
                (string) $commit,
                $parenthesis,
                $issuesToReference !== [] ? ', ' . implode(', ', $issuesToReference) : '',
                $this->createThanks($thanks)
            );

            // just to show off :)
            $output->writeln($changelogLine);

            $changelogLines[] = $changelogLine;

            // not to throttle the GitHub API
            if ($i > 0 && $i % 8 === 0) {
                sleep(60);
            }

            ++$i;
        }

        $releaseChangelogContents = $this->changelogContentsFactory->create($changelogLines);

        $filePath = getcwd() . '/next-release-changelog.md';

        FileSystem::write($filePath, $releaseChangelogContents);
        $output->write(sprintf('Changelog dumped into "%s" file', $filePath));

        return self::SUCCESS;
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
