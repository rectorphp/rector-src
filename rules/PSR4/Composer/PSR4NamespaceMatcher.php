<?php

declare(strict_types=1);

namespace Rector\PSR4\Composer;

use Nette\Utils\Strings;
use PhpParser\Node;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\ValueObject\Application\File;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;

/**
 * @see \Rector\Tests\PSR4\Composer\PSR4NamespaceMatcherTest
 */
final class PSR4NamespaceMatcher implements PSR4AutoloadNamespaceMatcherInterface
{
    public function __construct(
        private readonly PSR4AutoloadPathsProvider $psr4AutoloadPathsProvider,
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    public function getExpectedNamespace(File $file, Node $node): ?string
    {
        $filePath = $file->getFilePath();
        $psr4Autoloads = $this->psr4AutoloadPathsProvider->provide();

        foreach ($psr4Autoloads as $namespace => $path) {
            // remove extra slash
            $paths = is_array($path) ? $path : [$path];

            foreach ($paths as $path) {
                $relativeFilePath = $this->filePathHelper->relativePath($filePath);
                $relativeDirectoryPath = dirname($relativeFilePath);

                $path = rtrim($path, '/');
                if (! \str_starts_with($relativeDirectoryPath, $path)) {
                    continue;
                }

                $expectedNamespace = $namespace . $this->resolveExtraNamespace($relativeDirectoryPath, $path);

                if (str_contains($expectedNamespace, '-')) {
                    return null;
                }

                return rtrim($expectedNamespace, '\\');
            }
        }

        return null;
    }

    /**
     * Get the extra path that is not included in root PSR-4 namespace
     */
    private function resolveExtraNamespace(string $relativeDirectoryPath, string $path): string
    {
        $extraNamespace = Strings::substring($relativeDirectoryPath, Strings::length($path) + 1);
        $extraNamespace = Strings::replace($extraNamespace, '#/#', '\\');

        return trim($extraNamespace);
    }
}
