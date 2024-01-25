<?php

declare(strict_types=1);

namespace Rector\Php\PhpVersionResolver;

use Composer\Semver\VersionParser;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Util\PhpVersionFactory;

/**
 * @see \Rector\Tests\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver\ProjectComposerJsonPhpVersionResolverTest
 */
final class ProjectComposerJsonPhpVersionResolver
{
    /**
     * @var null|array<string, int|null>
     */
    private null|array $cachedPhpVersions = null;

    public function __construct(
        private readonly VersionParser $versionParser,
        private readonly PhpVersionFactory $phpVersionFactory
    ) {
    }

    public function resolve(string $composerJson): ?int
    {
        // value of cached php version can be null
        if ($this->cachedPhpVersions !== null && array_key_exists($composerJson, $this->cachedPhpVersions)) {
            return $this->cachedPhpVersions[$composerJson];
        }

        $composerJsonContents = FileSystem::read($composerJson);
        $projectComposerJson = Json::decode($composerJsonContents, Json::FORCE_ARRAY);

        // see https://getcomposer.org/doc/06-config.md#platform
        $platformPhp = $projectComposerJson['config']['platform']['php'] ?? null;
        if ($platformPhp !== null) {
            $this->cachedPhpVersions[$composerJson] = $this->phpVersionFactory->createIntVersion($platformPhp);
            return $this->cachedPhpVersions[$composerJson];
        }

        $requirePhpVersion = $projectComposerJson['require']['php'] ?? null;
        if ($requirePhpVersion === null) {
            return $this->cachedPhpVersions[$composerJson] = null;
        }

        $this->cachedPhpVersions[$composerJson] = $this->createIntVersionFromComposerVersion($requirePhpVersion);
        return $this->cachedPhpVersions[$composerJson];
    }

    private function createIntVersionFromComposerVersion(string $projectPhpVersion): int
    {
        $constraint = $this->versionParser->parseConstraints($projectPhpVersion);

        $lowerBound = $constraint->getLowerBound();
        $lowerBoundVersion = $lowerBound->getVersion();

        return $this->phpVersionFactory->createIntVersion($lowerBoundVersion);
    }
}
