<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\Printer;

use PhpParser\BuilderHelpers;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFalseNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantFloatType;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\Php80\ValueObject\AnnotationToAttribute;

final class PhpAttributeGroupFactory
{
    public function createFromSimpleTag(AnnotationToAttribute $annotationToAttribute): AttributeGroup
    {
        return $this->createFromClass($annotationToAttribute->getAttributeClass());
    }

    public function createFromClass(string $attributeClass): AttributeGroup
    {
        $fullyQualified = new FullyQualified($attributeClass);
        $attribute = new Attribute($fullyQualified);
        return new AttributeGroup([$attribute]);
    }

    public function create(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        AnnotationToAttribute $annotationToAttribute
    ): AttributeGroup {
        $fullyQualified = new FullyQualified($annotationToAttribute->getAttributeClass());

        $values = $doctrineAnnotationTagValueNode->getValuesWithExplicitSilentAndWithoutQuotes();

        $args = $this->createArgsFromItems($values);

        $attribute = new Attribute($fullyQualified, $args);
        return new AttributeGroup([$attribute]);
    }

    /**
     * @param mixed[] $items
     * @return Arg[]
     */
    private function createArgsFromItems(array $items, ?string $silentKey = null): array
    {
        $args = [];

        if ($silentKey !== null && isset($items[$silentKey])) {
            $silentValue = BuilderHelpers::normalizeValue($items[$silentKey]);
            $args[] = new Arg($silentValue);
            unset($items[$silentKey]);
        }

        foreach ($items as $key => $value) {
            $value = $this->normalizeNodeValue($value);
            $value = BuilderHelpers::normalizeValue($value);

            $args[] = $this->isArrayArguments($items)
                ? new Arg($value, false, false, [], new Identifier($key))
                : new Arg($value)
                ;
        }

        return $args;
    }

    /**
     * @param mixed[] $items
     */
    private function isArrayArguments(array $items): bool
    {
        foreach (array_keys($items) as $key) {
            if (! is_int($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return bool|float|int|string|array<mixed>
     */
    private function normalizeNodeValue($value)
    {
        if ($value instanceof ConstExprIntegerNode) {
            return (int) $value->value;
        }

        if ($value instanceof ConstantFloatType) {
            return $value->getValue();
        }

        if ($value instanceof ConstantBooleanType) {
            return $value->getValue();
        }

        if ($value instanceof ConstExprTrueNode) {
            return true;
        }

        if ($value instanceof ConstExprFalseNode) {
            return false;
        }

        if ($value instanceof CurlyListNode) {
            return array_map(
                fn ($node) => $this->normalizeNodeValue($node),
                $value->getValuesWithExplicitSilentAndWithoutQuotes()
            );
        }

        if ($value instanceof Node) {
            return (string) $value;
        }

        return $value;
    }
}
