<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDoc;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\ValueObject\Type\NonExistingObjectType;

final class CustomPHPStanDetector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly TypeComparator $typeComparator
    ) {
    }

    public function isCustomType(Type $definedType, Type $targetType, Node $node): bool
    {
        if (! $definedType instanceof NonExistingObjectType) {
            return false;
        }

        if ($this->typeComparator->areTypesEqual($definedType, $targetType)) {
            return false;
        }

        // start from current Node to lookup parent
        $parentNode = $node;

        while ($parentNode instanceof Node) {
            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($parentNode);
            $tagsByName = $phpDocInfo->getTagsByName('phpstan-import-type');

            foreach ($tagsByName as $tags) {
                if (! $tags->value instanceof TypeAliasImportTagValueNode) {
                    continue;
                }

                if ($tags->value->importedAlias === $definedType->getClassName()) {
                    return true;
                }
            }

            $parentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
        }

        return false;
    }
}
