<?php

declare(strict_types=1);

namespace Rector\Core\Configuration;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\ComplementaryRectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ConfigInitializer
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private readonly array $rectors,
        private readonly SymfonyStyle $symfonyStyle
    ) {
    }

    public function createConfig(string $projectDirectory): void
    {
        // we've found some rules → no need to create config
        if ($this->filterActiveRectors($this->rectors) !== []) {
            return;
        }

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

        $rectorPhpTemplateContents = \Nette\Utils\FileSystem::read(__DIR__ . '/../../templates/rector.php.dist');

        $rectorPhpTemplateContents = $this->replacePhpLevelContents($rectorPhpTemplateContents);
        $configContents = $this->replacePathsContents($rectorPhpTemplateContents, $projectDirectory);

        \Nette\Utils\FileSystem::write($commonRectorConfigPath, $configContents);
        $this->symfonyStyle->success('The config is added now. Re-run command to make Rector do the work!');
    }

    /**
     * @param RectorInterface[] $rectors
     * @return RectorInterface[]
     */
    private function filterActiveRectors(array $rectors): array
    {
        return array_filter(
            $rectors,
            static function (RectorInterface $rector): bool {
                if ($rector instanceof PostRectorInterface) {
                    return false;
                }

                return ! $rector instanceof ComplementaryRectorInterface;
            }
        );
    }
}
