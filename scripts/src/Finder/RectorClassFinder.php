<?php

declare(strict_types=1);

namespace Rector\Scripts\Finder;

use Nette\Loaders\RobotLoader;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;

final class RectorClassFinder
{
    /**
     * @param string[] $dirs
     * @return class-string[]
     */
    public function find(array $dirs): array
    {
        $robotLoader = new RobotLoader();
        $robotLoader->acceptFiles = ['*Rector.php'];
        $robotLoader->addDirectory(...$dirs);

        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/rector-rules');
        $robotLoader->refresh();

        /** @var array<class-string> $rectorClasses */
        $rectorClasses = array_keys($robotLoader->getIndexedClasses());

        $usableRectorClasses = [];

        // remove deprecated and abstract classes
        foreach ($rectorClasses as $rectorClass) {
            $rectorClassReflection = new \ReflectionClass($rectorClass);
            if ($rectorClassReflection->isAbstract()) {
                continue;
            }

            if ($rectorClassReflection->implementsInterface(DeprecatedInterface::class)) {
                continue;
            }

            $usableRectorClasses[] = $rectorClass;
        }

        return $usableRectorClasses;
    }
}
