<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\Printer;

use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\PhpAttribute\AnnotationToAttributeMapper;
use Rector\PhpAttribute\NodeAnalyzer\ExprParameterReflectionTypeCorrector;
use Rector\PhpAttribute\NodeAnalyzer\NamedArgumentsResolver;
use Rector\PhpAttribute\NodeFactory\AttributeNameFactory;
use Rector\PhpAttribute\NodeFactory\NamedArgsFactory;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\PhpAttribute\Printer\PhpAttributeGroupFactoryTest
 */
final class PhpAttributeGroupFactory
{
    /**
     * @var array<string, string[]>>
     */
    private const UNWRAPPED_ANNOTATIONS = [
        'Doctrine\ORM\Mapping\Table' => ['uniqueConstraints'],
    ];

    public function __construct(
        private readonly NamedArgumentsResolver $namedArgumentsResolver,
        private readonly AnnotationToAttributeMapper $annotationToAttributeMapper,
        private readonly AttributeNameFactory $attributeNameFactory,
        private readonly NamedArgsFactory $namedArgsFactory,
        private readonly ExprParameterReflectionTypeCorrector $exprParameterReflectionTypeCorrector
    ) {
    }

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

    /**
     * @param mixed[] $items
     */
    public function createFromClassWithItems(string $attributeClass, array $items): AttributeGroup
    {
        $fullyQualified = new FullyQualified($attributeClass);
        $args = $this->createArgsFromItems($items, $attributeClass);
        $attribute = new Attribute($fullyQualified, $args);

        return new AttributeGroup([$attribute]);
    }

    public function create(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        AnnotationToAttribute $annotationToAttribute,
    ): AttributeGroup {
        $values = $doctrineAnnotationTagValueNode->getValuesWithExplicitSilentAndWithoutQuotes();

        $args = $this->createArgsFromItems($values, $annotationToAttribute->getAttributeClass());

        // @todo this can be a different class then the unwrapped crated one
        $argumentNames = $this->namedArgumentsResolver->resolveFromClass($annotationToAttribute->getAttributeClass());

        $args = $this->completeNamedArguments($args, $argumentNames);

        $attributeName = $this->attributeNameFactory->create($annotationToAttribute, $doctrineAnnotationTagValueNode);

        $attribute = new Attribute($attributeName, $args);
        return new AttributeGroup([$attribute]);
    }

    /**
     * @param mixed[] $items
     * @return Arg[]
     */
    public function createArgsFromItems(array $items, string $attributeClass): array
    {
        /** @var Expr[]|Expr\Array_ $items */
        $items = $this->annotationToAttributeMapper->map($items);

        $items = $this->exprParameterReflectionTypeCorrector->correctItemsByAttributeClass($items, $attributeClass);

        $items = $this->removeUnwrappedItems($attributeClass, $items);

        return $this->namedArgsFactory->createFromValues($items);
    }

    /**
     * @param Arg[] $args
     * @param string[] $argumentNames
     * @return Arg[]
     */
    private function completeNamedArguments(array $args, array $argumentNames): array
    {
        Assert::allIsAOf($args, Arg::class);

        // matching implicit key
        if (count($argumentNames) === 1 && count($args) === 1) {
            $args[0]->name = new Identifier($argumentNames[0]);
        }

        $newArgs = [];

        foreach ($args as $arg) {

            // matching top root array key
            if ($arg->value instanceof ArrayItem) {
                $arrayItem = $arg->value;
                if ($arrayItem->key instanceof String_) {
                    $arrayItemString = $arrayItem->key;
                    $newArgs[] = new Arg(
                        $arrayItem->value,
                        false,
                        false,
                        [],
                        new Identifier($arrayItemString->value)
                    );
                }
            }
        }

        if (count($newArgs)) {
            return $newArgs;
        }

        return $args;
    }

    /**
     * @param mixed[] $items
     * @return mixed[]
     */
    private function removeUnwrappedItems(string $attributeClass, array $items): array
    {
        // unshift annotations that can be extracted
        $unwrappeColumns = self::UNWRAPPED_ANNOTATIONS[$attributeClass] ?? [];
        if ($unwrappeColumns === []) {
            return $items;
        }

        foreach ($items as $key => $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }
            $arrayItemKey = $item->key;
            if (! $arrayItemKey instanceof String_) {
                continue;
            }
            if (! in_array($arrayItemKey->value, $unwrappeColumns, true)) {
                continue;
            }

            unset($items[$key]);
        }

        return $items;
    }
}
