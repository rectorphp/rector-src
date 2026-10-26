<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\RepeatedThreeTimes\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\RuleDefinition;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @param array|SomeCollection $items → @param SomeCollection $items
 */
final class NarrowParamUnionDocblockRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly StaticTypeMapper $staticTypeMapper
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
        foreach ($phpDocInfo->getParamTagValueNodes() as $paramTagValueNode) {
            if (! $paramTagValueNode->type instanceof UnionTypeNode) {
                continue;
            }

            if (count($paramTagValueNode->type->types) !== 2) {
                continue;
            }

            [$firstTypeNode, $secondTypeNode] = $paramTagValueNode->type->types;
            if (! $firstTypeNode instanceof IdentifierTypeNode || $firstTypeNode->name !== 'array') {
                continue;
            }

            $secondType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($secondTypeNode, $node);
            if (! $secondType->equals(new ObjectType(SomeCollection::class))) {
                continue;
            }

            $paramTagValueNode->type = $secondTypeNode;
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }
}
