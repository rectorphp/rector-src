<?php

declare(strict_types=1);

namespace Rector\Bridge\PhpParser;

use Nette\Loaders\RobotLoader;
use PHPStan\Node\AnonymousClassNode;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
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

        $instantiableNodeClasses = array_filter($nodeClasses, function (string $nodeClass): bool {
            $nodeClassReflection = new ReflectionClass($nodeClass);
            if ($nodeClassReflection->isAbstract()) {
                return false;
            }

            return ! $nodeClassReflection->isInterface();
        });

        // this is needed, as PHPStan replaces the Class_ node of anonymous class completely
        // @see https://github.com/phpstan/phpstan-src/blob/2.1.x/src/Parser/AnonymousClassVisitor.php
        $specialPHPStanNodes = [AnonymousClassNode::class];

        $specialRectorNodes = [FileWithoutNamespace::class];

        return array_merge($instantiableNodeClasses, $specialPHPStanNodes, $specialRectorNodes);
    }
}
