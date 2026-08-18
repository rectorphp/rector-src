<?php

declare(strict_types=1);

namespace Rector\Scripts\Finder;

use Deprecated;
use Nette\Loaders\RobotLoader;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use ReflectionClass;

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

        $robotLoader->setCacheDirectory(sys_get_temp_dir() . '/rector-rules');
        $robotLoader->refresh();

        /** @var array<class-string> $rectorClasses */
        $rectorClasses = array_keys($robotLoader->getIndexedClasses());

        $usableRectorClasses = [];

        // remove deprecated and abstract classes
        foreach ($rectorClasses as $rectorClass) {
            $rectorClassReflection = new ReflectionClass($rectorClass);
            if ($rectorClassReflection->isAbstract()) {
                continue;
            }

            if ($rectorClassReflection->implementsInterface(DeprecatedInterface::class)) {
                continue;
            }

            if ($this->isDeprecated($rectorClassReflection)) {
                continue;
            }

            $usableRectorClasses[] = $rectorClass;
        }

        return $usableRectorClasses;
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     */
    private function isDeprecated(ReflectionClass $reflectionClass): bool
    {
        if ($reflectionClass->getAttributes(Deprecated::class) !== []) {
            return true;
        }

        $docComment = $reflectionClass->getDocComment();
        if (! is_string($docComment)) {
            return false;
        }

        return str_contains($docComment, '@deprecated');
    }
}
