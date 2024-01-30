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
        foreach ($filePaths as $filePath) {
            if (str_contains(PathNormalizer::normalize($filePath), '/vendor/')) {
                return true;
            }
        }

        return false;
    }
}
