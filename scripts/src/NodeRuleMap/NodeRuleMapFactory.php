<?php

declare(strict_types=1);

namespace Rector\Scripts\NodeRuleMap;

use Nette\Loaders\RobotLoader;
use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use Rector\Scripts\Finder\RectorClassFinder;
use ReflectionClass;

final class NodeRuleMapFactory
{
    public function __construct(
        private readonly RectorClassFinder $rectorClassFinder
    ) {
    }

    /**
     * @param string[] $ruleDirectories
     * @param string[] $nodeDirectories
     * @return array<class-string<Node>, array<class-string<RectorInterface>>>
     */
    public function create(array $ruleDirectories, array $nodeDirectories): array
    {
        $nodeTypesByRectorClass = $this->resolveNodeTypesByRectorClass($ruleDirectories);
        $nodeClasses = $this->resolveNodeClasses($nodeDirectories);

        $nodeRuleMap = [];
        foreach ($nodeClasses as $nodeClass) {
            $matchedRectorClasses = [];
            foreach ($nodeTypesByRectorClass as $rectorClass => $nodeTypes) {
                foreach ($nodeTypes as $nodeType) {
                    if (is_a($nodeClass, $nodeType, true)) {
                        $matchedRectorClasses[] = $rectorClass;
                        break;
                    }
                }
            }

            sort($matchedRectorClasses);
            $nodeRuleMap[$nodeClass] = $matchedRectorClasses;
        }

        ksort($nodeRuleMap);

        return $nodeRuleMap;
    }

    /**
     * @param string[] $ruleDirectories
     * @return array<class-string<RectorInterface>, array<class-string<Node>>>
     */
    private function resolveNodeTypesByRectorClass(array $ruleDirectories): array
    {
        $ruleDirectories = array_filter($ruleDirectories, 'is_dir');
        $rectorClasses = $this->rectorClassFinder->find($ruleDirectories);

        $nodeTypesByRectorClass = [];
        foreach ($rectorClasses as $rectorClass) {
            // skip PostRectors and other *Rector.php helpers
            if (! is_a($rectorClass, RectorInterface::class, true)) {
                continue;
            }

            $reflectionClass = new ReflectionClass($rectorClass);
            $rector = $reflectionClass->newInstanceWithoutConstructor();

            $nodeTypesByRectorClass[$rectorClass] = $rector->getNodeTypes();
        }

        return $nodeTypesByRectorClass;
    }

    /**
     * @param string[] $nodeDirectories
     * @return array<class-string<Node>>
     */
    private function resolveNodeClasses(array $nodeDirectories): array
    {
        $robotLoader = new RobotLoader();
        $robotLoader->addDirectory(...$nodeDirectories);
        $robotLoader->setCacheDirectory(sys_get_temp_dir() . '/rector-node-rule-map');
        $robotLoader->refresh();

        $nodeClasses = [];
        foreach (array_keys($robotLoader->getIndexedClasses()) as $class) {
            if (! is_a($class, Node::class, true)) {
                continue;
            }

            if (! (new ReflectionClass($class))->isInstantiable()) {
                continue;
            }

            $nodeClasses[] = $class;
        }

        return $nodeClasses;
    }
}
