<?php

declare(strict_types=1);

namespace Rector\Core\Php\PhpVersionResolver;

use Composer\Semver\VersionParser;
use Rector\Core\Util\PhpVersionFactory;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;

/**
 * @see \Rector\Core\Tests\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver\ProjectComposerJsonPhpVersionResolverTest
 */
final class ProjectComposerJsonPhpVersionResolver
{
    public function __construct(
        private readonly ComposerJsonFactory $composerJsonFactory,
        private readonly VersionParser $versionParser,
        private readonly PhpVersionFactory $phpVersionFactory
    ) {
    }

    public function resolve(string $composerJson): ?int
    {
        $projectComposerJson = $this->composerJsonFactory->createFromFilePath($composerJson);

        // see https://getcomposer.org/doc/06-config.md#platform
        $platformPhp = $projectComposerJson->getConfig()['platform']['php'] ?? null;
        if ($platformPhp !== null) {
            return $this->phpVersionFactory->createIntVersion($platformPhp);
        }

        $requirePhpVersion = $projectComposerJson->getRequirePhpVersion();
        if ($requirePhpVersion === null) {
            return null;
        }

        return $this->createIntVersionFromComposerVersion($requirePhpVersion);
    }

    private function createIntVersionFromComposerVersion(string $projectPhpVersion): int
    {
        $constraint = $this->versionParser->parseConstraints($projectPhpVersion);

        $lowerBound = $constraint->getLowerBound();
        $lowerBoundVersion = $lowerBound->getVersion();

        return $this->phpVersionFactory->createIntVersion($lowerBoundVersion);
    }
}
