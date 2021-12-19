<?php

declare(strict_types=1);

namespace Rector\PhpAttribute;

use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\ValueObject\AnnotationToAttribute;

final class UnwrapableAnnotationAnalyzer
{
    /**
     * List of annotation classes that can be un-wrapped
     * @see https://github.com/doctrine/orm/commit/b6b3c974361d7042e4b7d868fb34daca76bb2a48 - repeatable attributes
     * @var string[]
     */
    private const UNWRAPEABLE_ANNOTATION_CLASSES = [
        'Doctrine\ORM\Mapping\UniqueConstraint',
        'Doctrine\ORM\Mapping\JoinColumn',
        'Doctrine\ORM\Mapping\Index',
        'Doctrine\ORM\Mapping\AttributeOverride',
    ];

    /**
     * @var AnnotationToAttribute[]
     */
    private array $annotationsToAttributes = [];

    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    /**
     * @param AnnotationToAttribute[] $annotationsToAttributes
     */
    public function configure(array $annotationsToAttributes): void
    {
        $this->annotationsToAttributes = $annotationsToAttributes;
    }

    /**
     * @param DoctrineAnnotationTagValueNode[] $doctrineAnnotationTagValueNodes
     */
    public function areUnwrappable(array $doctrineAnnotationTagValueNodes): bool
    {
        // the new in initilazers is handled directly
        if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::NEW_INITIALIZERS)) {
            return false;
        }

        foreach ($doctrineAnnotationTagValueNodes as $doctrineAnnotationTagValueNode) {
            $annotationClassName = $doctrineAnnotationTagValueNode->identifierTypeNode->getAttribute(
                PhpDocAttributeKey::RESOLVED_CLASS
            );

            $nestedAnnotationToAttribute = $this->matchAnnotationToAttribute($doctrineAnnotationTagValueNode);

            // the nested annotation should be convertable
            if (! $nestedAnnotationToAttribute instanceof AnnotationToAttribute) {
                return false;
            }

            if (! in_array($annotationClassName, self::UNWRAPEABLE_ANNOTATION_CLASSES, true)) {
                return false;
            }
        }

        return true;
    }

    private function matchAnnotationToAttribute(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode
    ): AnnotationToAttribute|null {
        foreach ($this->annotationsToAttributes as $annotationToAttribute) {
            if (! $doctrineAnnotationTagValueNode->hasClassName($annotationToAttribute->getTag())) {
                continue;
            }

            return $annotationToAttribute;
        }

        return null;
    }
}
