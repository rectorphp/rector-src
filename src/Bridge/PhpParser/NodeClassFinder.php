<?php

declare(strict_types=1);

namespace Rector\Bridge\PhpParser;

use Nette\Loaders\RobotLoader;
use ReflectionClass;

final class NodeClassFinder
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public static function find(): array
    {
        $robotLoader = new RobotLoader();
        $robotLoader->acceptFiles = ['*.php'];

        $phpParserNodeDirectory = __DIR__ . '/../../../vendor/nikic/php-parser/lib/PhpParser/Node/';
        $robotLoader->addDirectory($phpParserNodeDirectory);

        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/node-classes');
        $robotLoader->refresh();

        /** @var array<class-string> $nodeClasses */
        $nodeClasses = array_keys($robotLoader->getIndexedClasses());

        return array_filter($nodeClasses, function (string $nodeClass): bool {
            $nodeClassReflection = new ReflectionClass($nodeClass);
            if ($nodeClassReflection->isAbstract()) {
                return false;
            }

            return ! $nodeClassReflection->isInterface();
        });
    }
}
