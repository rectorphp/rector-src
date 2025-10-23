<?php

declare(strict_types=1);

namespace Rector\Scripts\Finder;

use Nette\Loaders\RobotLoader;

final class RectorClassFinder
{
    /**
     * @param string[] $dirs
     * @return string[]
     */
    public function find(array $dirs): array
    {
        $robotLoader = new RobotLoader();
        $robotLoader->acceptFiles = ['*Rector.php'];
        $robotLoader->addDirectory(...$dirs);

        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/rector-rules');
        $robotLoader->refresh();

        return array_keys($robotLoader->getIndexedClasses());
    }
}
