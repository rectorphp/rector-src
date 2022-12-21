<?php

declare(strict_types=1);

use Rector\Utils\ChangelogGenerator\Changelog\ChangelogContentsFactory;
use Rector\Utils\ChangelogGenerator\Command\GenerateChangelogCommand;
use Rector\Utils\ChangelogGenerator\GithubApiCaller;
use Rector\Utils\ChangelogGenerator\GitResolver;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(~E_DEPRECATED);

$githubToken = getenv('GITHUB_TOKEN');
$githubApiCaller = new GithubApiCaller($githubToken);

$generateChangelogCommand = new GenerateChangelogCommand(
    new GitResolver(),
    $githubApiCaller,
    new ChangelogContentsFactory()
);

$application = new Application();
$application->add($generateChangelogCommand);
$application->setDefaultCommand('generate-changelog', true);
$application->run();
