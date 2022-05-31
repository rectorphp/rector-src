<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\NodeFactory;

use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Use_;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\PhpAttribute\UseAliasNameMatcher;
use Rector\PhpAttribute\ValueObject\UseAliasMetadata;

final class AttributeNameFactory
{
    public function __construct(
        private readonly UseAliasNameMatcher $useAliasNameMatcher
    ) {
    }

    /**
     * @param Use_[] $uses
     */
    public function create(
        AnnotationToAttribute $annotationToAttribute,
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        array $uses
    ): FullyQualified|Name {
        // A. attribute and class name are the same, so we re-use the short form to keep code compatible with previous one
        if ($annotationToAttribute->getAttributeClass() === $annotationToAttribute->getTag()) {
            $attributeName = $doctrineAnnotationTagValueNode->identifierTypeNode->name;
            $attributeName = ltrim($attributeName, '@');

            return new Name($attributeName);
        }

        // B. different name
        $useAliasMetadata = $this->useAliasNameMatcher->match(
            $uses,
            $doctrineAnnotationTagValueNode->identifierTypeNode->name,
            $annotationToAttribute
        );
        if ($useAliasMetadata instanceof UseAliasMetadata) {
            $useUse = $useAliasMetadata->getUseUse();

            // is same as name?
            $useImportName = $useAliasMetadata->getUseImportName();
            if ($useUse->name->toString() !== $useImportName) {
                // no? rename
                $useUse->name = new Name($useImportName);
            }

            return new Name($useAliasMetadata->getShortAttributeName());
        }

        // 3. the class is not aliased and is compeltelly new... return the FQN version
        return new FullyQualified($annotationToAttribute->getAttributeClass());
    }
}
