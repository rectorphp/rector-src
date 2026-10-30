<?php

declare(strict_types=1);

use Nette\Utils\Strings;
use Rector\Scripts\Finder\FixtureFinder;

require __DIR__ . '/../vendor/autoload.php';
use Rector\Console\ExitCode;
use Rector\Console\Style\RectorStyle;
use Rector\Console\Style\RectorStyleFactory;
use Symfony\Component\Finder\SplFileInfo;

final readonly class SameBeforeAfterFixtureDetector
{
    private RectorStyle $rectorStyle;

    public function __construct()
    {
        $this->rectorStyle = new RectorStyleFactory()->create();
    }

    /**
     * @param string[] $testDirectories
     */
    public function run(array $testDirectories): int
    {
        $fixtureFiles = FixtureFinder::find($testDirectories);

        $invalidFixturePaths = [];
        foreach ($fixtureFiles as $fixtureFile) {
            if (! $this->hasFileSameBeforeAndAfterPart($fixtureFile)) {
                continue;
            }

            $invalidFixturePaths[] = substr($fixtureFile->getRealPath(), strlen(getcwd()) + 1);
        }

        if ($invalidFixturePaths === []) {
            $this->rectorStyle->success(sprintf('All %d fixtures are valid', count($fixtureFiles)));
            return ExitCode::SUCCESS;
        }

        $this->rectorStyle->error(
            'The following fixtures have the same before and after content. Remove the part after "-----" to fix them'
        );
        $this->rectorStyle->listing($invalidFixturePaths);

        return ExitCode::FAILURE;
    }

    private function hasFileSameBeforeAndAfterPart(SplFileInfo $fixtureFile): bool
    {
        $parts = Strings::split($fixtureFile->getContents(), '#^\s*-----\s*$#m');
        if (count($parts) !== 2) {
            return false;
        }

        return trim($parts[0]) === trim($parts[1]);
    }
}

$sameBeforeAfterFixtureDetector = new SameBeforeAfterFixtureDetector();
exit($sameBeforeAfterFixtureDetector->run([__DIR__ . '/../tests', __DIR__ . '/../rules-tests']));
