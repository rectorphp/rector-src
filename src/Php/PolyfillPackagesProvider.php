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
     * @var null|array<int, PolyfillPackage::*>
     */
    private null|array $cachedPolyfillPackages = null;

    /**
     * @return array<int, PolyfillPackage::*>
     */
    public function provide(): array
    {
        // used in tests mostly
        if (SimpleParameterProvider::hasParameter(Option::POLYFILL_PACKAGES)) {
            return SimpleParameterProvider::provideArrayParameter(Option::POLYFILL_PACKAGES);
        }

        // already cached, even only empty array
        if ($this->cachedPolyfillPackages !== null) {
            return $this->cachedPolyfillPackages;
        }

        $projectComposerJson = getcwd() . '/composer.json';
        if (! file_exists($projectComposerJson)) {
            $this->cachedPolyfillPackages = [];
            return $this->cachedPolyfillPackages;
        }

        $composerContents = FileSystem::read($projectComposerJson);
        $composerJson = Json::decode($composerContents, Json::FORCE_ARRAY);

        $this->cachedPolyfillPackages = $this->filterPolyfillPackages($composerJson['require'] ?? []);

        return $this->cachedPolyfillPackages;
    }

    /**
     * @param array<string, string> $require
     * @return array<int, PolyfillPackage::*>
     */
    private function filterPolyfillPackages(array $require): array
    {
        return array_filter(array_keys($require), static fn (string $packageName): bool => str_starts_with(
            $packageName,
            'symfony/polyfill-'
        ));
    }
}
