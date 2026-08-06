<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final readonly class NoPhpFileInFixturesDetector
{
    /**
     * @var string[]
     */
    private const array EXCLUDED_FILES = [
        // on-purpose as same namespace text
        'rules-tests/Renaming/Rector/Name/RenameClassRector/FixtureAutoImportNames/SomeShort.php',
    ];

    private SymfonyStyle $symfonyStyle;

    public function __construct()
    {
        $this->symfonyStyle = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
    }

    /**
     * @param string[] $ruleTestDirectories
     * @param string[] $testDirectories
     * @return Command::SUCCESS|Command::FAILURE
     */
    public function run(array $ruleTestDirectories, array $testDirectories): int
    {
        $allFixtureFiles = $this->findFixtureFiles($ruleTestDirectories);

        $invalidFiles = [
            ...$this->findPhpFiles($ruleTestDirectories),
            // ".inc" alone is never picked up, only "*.php.inc" is
            ...$this->findIncompleteIncFiles([...$ruleTestDirectories, ...$testDirectories]),
        ];

        $relativeInvalidFilePaths = [];
        foreach ($invalidFiles as $invalidFile) {
            $relativeFilePath = substr($invalidFile->getRealPath(), strlen(getcwd()) + 1);

            // should skip?
            if (in_array($relativeFilePath, self::EXCLUDED_FILES, true)) {
                continue;
            }

            $relativeInvalidFilePaths[] = $relativeFilePath;
        }

        if ($relativeInvalidFilePaths === []) {
            $this->symfonyStyle->success(sprintf('All %d fixtures are valid', count($allFixtureFiles)));
            return Command::SUCCESS;
        }

        $this->symfonyStyle->error(
            'The following files were found in /Fixtures directory, but only "*.php.inc" files are picked up and allowed. Rename their suffix or remove them'
        );
        $this->symfonyStyle->listing($relativeInvalidFilePaths);

        return Command::FAILURE;
    }

    /**
     * @param string[] $directories
     * @return SplFileInfo[]
     */
    private function findPhpFiles(array $directories): array
    {
        Assert::allDirectory($directories);

        $finder = new Finder()
            ->files()
            ->in($directories)
            ->path('/Fixture')
            ->path('/Fixture*')
            ->notPath('Source')
            ->name('*.php')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @param string[] $directories
     * @return SplFileInfo[]
     */
    private function findIncompleteIncFiles(array $directories): array
    {
        Assert::allDirectory($directories);

        $finder = new Finder()
            ->files()
            ->in($directories)
            ->path('/Fixture')
            ->path('/Fixture*')
            ->notPath('Source')
            ->name('*.inc')
            ->notName('*.php.inc')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @param string[] $directories
     * @return SplFileInfo[]
     */
    private function findFixtureFiles(array $directories): array
    {
        Assert::allDirectory($directories);

        $finder = new Finder()
            ->files()
            ->in($directories)
            ->path('Fixture')
            ->path('Fixture*')
            ->notPath('Source')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }
}

$noPhpFileInFixturesDetector = new NoPhpFileInFixturesDetector();

exit($noPhpFileInFixturesDetector->run([__DIR__ . '/../rules-tests'], [__DIR__ . '/../tests']));
