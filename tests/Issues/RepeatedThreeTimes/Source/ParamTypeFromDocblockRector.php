<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\RepeatedThreeTimes\Source;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\RuleDefinition;

/**
 * @param SomeCollection $items → native SomeCollection $items param type
 */
final class ParamTypeFromDocblockRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?ClassMethod
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo === null) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->params as $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            $paramType = $phpDocInfo->getParamType($this->getName($param));
            if (! $paramType->equals(new ObjectType(SomeCollection::class))) {
                continue;
            }

            $param->type = new FullyQualified(SomeCollection::class);
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }
}
