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
        /** @var string[] $registeredRectorSets */
        $registeredRectorSets = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_SETS);
<<<<<<< HEAD

        return array_any(
            $registeredRectorSets,
            fn (string $registeredRectorSet): bool => str_contains($registeredRectorSet, 'downgrade-')
=======
        return array_any(
            $registeredRectorSets,
            fn ($registeredRectorSet): bool => str_contains((string) $registeredRectorSet, 'downgrade-')
>>>>>>> 0ce3dbd107 ([php] bump to PHP 8.4 syntax)
        );
    }

    /**
     * @param string[] $filePaths
     */
    private function containsVendorPath(array $filePaths): bool
    {
        $cwdLength = strlen(getcwd());

        foreach ($filePaths as $filePath) {
            $normalizedPath = PathNormalizer::normalize(realpath($filePath));
            if (str_starts_with(substr($normalizedPath, $cwdLength), '/vendor/')) {
                return true;
            }
        }

        return false;
    }
}
