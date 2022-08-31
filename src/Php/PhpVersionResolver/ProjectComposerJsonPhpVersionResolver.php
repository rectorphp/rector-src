<?php

declare(strict_types=1);

namespace Rector\Core\Php\PhpVersionResolver;

use Composer\Semver\VersionParser;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Core\Util\PhpVersionFactory;

/**
 * @see \Rector\Core\Tests\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver\ProjectComposerJsonPhpVersionResolverTest
 */
final class ProjectComposerJsonPhpVersionResolver
{
    public function __construct(
        private readonly VersionParser $versionParser,
        private readonly PhpVersionFactory $phpVersionFactory
    ) {
    }

    public function resolve(string $composerJson): ?int
    {
        $composerJsonContents = FileSystem::read($composerJson);
        $projectComposerJson = Json::decode($composerJsonContents, Json::FORCE_ARRAY);

        // see https://getcomposer.org/doc/06-config.md#platform
        $platformPhp = $projectComposerJson['config']['platform']['php'] ?? null;
        if ($platformPhp !== null) {
            return $this->phpVersionFactory->createIntVersion($platformPhp);
        }

        $requirePhpVersion = $projectComposerJson['require']['php'] ?? null;
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
