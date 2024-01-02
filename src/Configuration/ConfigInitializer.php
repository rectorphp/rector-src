<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Contract\Rector\RectorInterface;
use Rector\FileSystem\InitFilePathsResolver;
use Rector\Php\PhpVersionProvider;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class ConfigInitializer
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private InitFilePathsResolver $initFilePathsResolver,
        private SymfonyStyle $symfonyStyle,
        private PhpVersionProvider $phpVersionProvider,
    ) {
    }

    public function createConfig(string $projectDirectory): void
    {
        $commonRectorConfigPath = $projectDirectory . '/rector.php';

        if (file_exists($commonRectorConfigPath)) {
            $this->symfonyStyle->warning('Register rules or sets in your "rector.php" config');
            return;
        }

        $response = $this->symfonyStyle->ask('No "rector.php" config found. Should we generate it for you?', 'yes');
        if ($response !== 'yes') {
            // okay, nothing we can do
            return;
        }

        $configContents = FileSystem::read(__DIR__ . '/../../templates/rector.php.dist');

        $configContents = $this->replacePhpLevelContents($configContents);

        $configContents = $this->replacePathsContents($configContents, $projectDirectory);

        FileSystem::write($commonRectorConfigPath, $configContents);
        $this->symfonyStyle->success('The config is added now. Re-run command to make Rector do the work!');
    }

    public function areSomeRectorsLoaded(): bool
    {
        $activeRectors = $this->filterActiveRectors($this->rectors);

        return $activeRectors !== [];
    }

    /**
     * @param RectorInterface[] $rectors
     * @return RectorInterface[]
     */
    private function filterActiveRectors(array $rectors): array
    {
        return array_filter(
            $rectors,
            static fn (RectorInterface $rector): bool => ! $rector instanceof PostRectorInterface
        );
    }

    private function replacePhpLevelContents(string $rectorPhpTemplateContents): string
    {
        $fullPHPVersion = (string) $this->phpVersionProvider->provide();
        $phpVersion = Strings::substring($fullPHPVersion, 0, 1) . Strings::substring($fullPHPVersion, 2, 1);

        return str_replace(
            'LevelSetList::UP_TO_PHP_XY',
            'LevelSetList::UP_TO_PHP_' . $phpVersion,
            $rectorPhpTemplateContents
        );
    }

    private function replacePathsContents(string $rectorPhpTemplateContents, string $projectDirectory): string
    {
        $projectPhpDirectories = $this->initFilePathsResolver->resolve($projectDirectory);

        // fallback to default 'src' in case of empty one
        if ($projectPhpDirectories === []) {
            $projectPhpDirectories[] = 'src';
        }

        $projectPhpDirectoriesContents = '';
        foreach ($projectPhpDirectories as $projectPhpDirectory) {
            $projectPhpDirectoriesContents .= "        __DIR__ . '/" . $projectPhpDirectory . "'," . PHP_EOL;
        }

        $projectPhpDirectoriesContents = rtrim($projectPhpDirectoriesContents);

        return str_replace('__PATHS__', $projectPhpDirectoriesContents, $rectorPhpTemplateContents);
    }
}
