<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\Attributes;

use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Node;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDoc\SpacelessPhpDocTagNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\BetterPhpDocParser\ValueObject\StartAndEnd;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\Configuration;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class AttributeMirrorer
{
    /**
     * @var string[]
     */
    private const ATTRIBUTES_TO_MIRROR = [
        PhpDocAttributeKey::PARENT,
        PhpDocAttributeKey::START_AND_END,
        PhpDocAttributeKey::ORIG_NODE,
    ];

    public function __construct(
        private readonly ParameterProvider $parameterProvider,
        private readonly ClassNameImportSkipper $classNameImportSkipper
    )
    {
    }

    public function mirror(Node $oldNode, Node $newNode): void
    {
        foreach (self::ATTRIBUTES_TO_MIRROR as $attributeToMirror) {
            if (! $oldNode->hasAttribute($attributeToMirror)) {
                continue;
            }

            $attributeValue = $oldNode->getAttribute($attributeToMirror);
            $newNode->setAttribute($attributeToMirror, $attributeValue);
        }
    }
}
