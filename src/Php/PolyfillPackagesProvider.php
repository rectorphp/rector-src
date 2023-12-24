<?php

declare(strict_types=1);

namespace Rector\Core\Php;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\ValueObject\PolyfillPackage;

final class PolyfillPackagesProvider
{
    /**
     * @var array<PolyfillPackage::*>
     */
    private array $cachedPolyfillPackages = [];

    /**
     * @return array<PolyfillPackage::*>
     */
    public function provide(): array
    {
        // used in tests mostly
        if (SimpleParameterProvider::hasParameter(Option::POLYFILL_PACKAGES)) {
            return SimpleParameterProvider::provideArrayParameter(Option::POLYFILL_PACKAGES);
        }

        $projectComposerJson = getcwd() . '/composer.json';
        if (! file_exists($projectComposerJson)) {
            return [];
        }

        if ($this->cachedPolyfillPackages !== []) {
            return $this->cachedPolyfillPackages;
        }

        $composerContents = FileSystem::read($projectComposerJson);
        $composerJson = Json::decode($composerContents, Json::FORCE_ARRAY);

        $this->cachedPolyfillPackages = $this->filterPolyfillPackages($composerJson['require'] ?? []);

        return $this->cachedPolyfillPackages;
    }

    /**
     * @param array<string, string> $require
     * @return array<PolyfillPackage::*>
     */
    private function filterPolyfillPackages(array $require): array
    {
        return array_filter($require, static fn (string $packageName): bool => ! str_starts_with(
            $packageName,
            'symfony/polyfill-'
        ));
    }
}
