<?php

declare(strict_types=1);

namespace Rector\Utils\Finder;

use Nette\Loaders\RobotLoader;
use Rector\Contract\Rector\RectorInterface;

final class RectorClassFinder
{
    /**
     * @param string[] $directories
     * @return array<class-string<RectorInterface>>
     */
    public static function find(array $directories): array
    {
        $robotLoader = new RobotLoader();
        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/rector-missing-in-set');
        $robotLoader->addDirectory(...$directories);

        $robotLoader->acceptFiles = ['*Rector.php'];
        $robotLoader->rebuild();

        $filePathsToRectorClasses = array_keys($robotLoader->getIndexedClasses());

        return array_filter(
            $filePathsToRectorClasses,
            static fn (string $rectorClass): bool => is_a($rectorClass, RectorInterface::class, true)
        );
    }
}
