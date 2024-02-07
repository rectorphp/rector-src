<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\FileSystem\PathNormalizer;

final class VendorMissAnalyseGuard
{
    /**
     * @param string[] $filePaths
     */
    public function isVendorAnalyzed(array $filePaths): bool
    {
        if ($this->hasDowngradeSets()) {
            return false;
        }

        return $this->containsVendorPath($filePaths);
    }

    private function hasDowngradeSets(): bool
    {
        $registeredRectorSets = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_SETS);

        foreach ($registeredRectorSets as $registeredRectorSet) {
            if (str_contains((string) $registeredRectorSet, 'downgrade-')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string[] $filePaths
     */
    private function containsVendorPath(array $filePaths): bool
    {
        $cwd = PathNormalizer::normalize(getcwd());

        foreach ($filePaths as $filePath) {
            $filePath = realpath($filePath);
            if ($filePath === false) {
                continue;
            }

            $normalizedPath = PathNormalizer::normalize($filePath);
            if (str_starts_with(substr($normalizedPath, strlen($cwd)), '/vendor')) {
                return true;
            }
        }

        return false;
    }
}
