<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocInfo;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocChildNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\Annotation\AnnotationNaming;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDoc\SpacelessPhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocNodeFinder\PhpDocNodeByTypeFinder;
use Rector\BetterPhpDocParser\ValueObject\Parser\BetterTokenIterator;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\BetterPhpDocParser\ValueObject\Type\ShortenedIdentifierTypeNode;
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @see \Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfo\PhpDocInfoTest
 */
final class PhpDocInfo
{
    /**
     * @var array<class-string<PhpDocTagValueNode>, string>
     */
    private const TAGS_TYPES_TO_NAMES = [
        ReturnTagValueNode::class => '@return',
        ParamTagValueNode::class => '@param',
        VarTagValueNode::class => '@var',
        MethodTagValueNode::class => '@method',
        PropertyTagValueNode::class => '@property',
        ExtendsTagValueNode::class => '@extends',
        ImplementsTagValueNode::class => '@implements',
    ];

    private bool $isSingleLine = false;

    private readonly PhpDocNode $originalPhpDocNode;

    public function __construct(
        private readonly PhpDocNode $phpDocNode,
        private readonly BetterTokenIterator $betterTokenIterator,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly \PhpParser\Node $node,
        private readonly AnnotationNaming $annotationNaming,
        private readonly PhpDocNodeByTypeFinder $phpDocNodeByTypeFinder
    ) {
        $this->originalPhpDocNode = clone $phpDocNode;

        if (! $betterTokenIterator->containsTokenType(Lexer::TOKEN_PHPDOC_EOL)) {
            $this->isSingleLine = true;
        }
    }

    /**
     * @api
     */
    public function addPhpDocTagNode(PhpDocChildNode $phpDocChildNode): void
    {
        $this->phpDocNode->children[] = $phpDocChildNode;
        // to give node more space
        $this->makeMultiLined();
    }

    public function getPhpDocNode(): PhpDocNode
    {
        return $this->phpDocNode;
    }

    public function getOriginalPhpDocNode(): PhpDocNode
    {
        return $this->originalPhpDocNode;
    }

    /**
     * @return mixed[]
     */
    public function getTokens(): array
    {
        return $this->betterTokenIterator->getTokens();
    }

    public function getTokenCount(): int
    {
        return $this->betterTokenIterator->count();
    }

    public function getVarTagValueNode(string $tagName = '@var'): ?VarTagValueNode
    {
        return $this->phpDocNode->getVarTagValues($tagName)[0] ?? null;
    }

    /**
     * @return array<PhpDocTagNode>
     */
    public function getTagsByName(string $name): array
    {
        // for simple tag names only
        if (str_contains($name, '\\')) {
            return [];
        }

        $tags = $this->phpDocNode->getTags();
        $name = $this->annotationNaming->normalizeName($name);

        $tags = array_filter($tags, static fn (PhpDocTagNode $phpDocTagNode): bool => $phpDocTagNode->name === $name);

        return array_values($tags);
    }

    public function getParamType(string $name): Type
    {
        $paramTagValueNodes = $this->getParamTagValueByName($name);
        return $this->getTypeOrMixed($paramTagValueNodes);
    }

    /**
     * @return ParamTagValueNode[]
     */
    public function getParamTagValueNodes(): array
    {
        return $this->phpDocNode->getParamTagValues();
    }

    public function getVarType(string $tagName = '@var'): Type
    {
        return $this->getTypeOrMixed($this->getVarTagValueNode($tagName));
    }

    public function getReturnType(): Type
    {
        return $this->getTypeOrMixed($this->getReturnTagValue());
    }

    /**
     * @param class-string<Node> $type
     */
    public function hasByType(string $type): bool
    {
        return $this->phpDocNodeByTypeFinder->findByType($this->phpDocNode, $type) !== [];
    }

    /**
     * @param array<class-string<Node>> $types
     */
    public function hasByTypes(array $types): bool
    {
        foreach ($types as $type) {
            if ($this->hasByType($type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string[] $names
     */
    public function hasByNames(array $names): bool
    {
        foreach ($names as $name) {
            if ($this->hasByName($name)) {
                return true;
            }
        }

        return false;
    }

    public function hasByName(string $name): bool
    {
        return (bool) $this->getTagsByName($name);
    }

    /**
     * @api
     */
    public function getByName(string $name): ?Node
    {
        return $this->getTagsByName($name)[0] ?? null;
    }

    /**
     * @param string[] $classes
     */
    public function getByAnnotationClasses(array $classes): ?DoctrineAnnotationTagValueNode
    {
        $doctrineAnnotationTagValueNodes = $this->phpDocNodeByTypeFinder->findDoctrineAnnotationsByClasses(
            $this->phpDocNode,
            $classes
        );

        return $doctrineAnnotationTagValueNodes[0] ?? null;
    }

    /**
     * @api doctrine/symfony
     */
    public function getByAnnotationClass(string $class): ?DoctrineAnnotationTagValueNode
    {
        $doctrineAnnotationTagValueNodes = $this->phpDocNodeByTypeFinder->findDoctrineAnnotationsByClass(
            $this->phpDocNode,
            $class
        );
        return $doctrineAnnotationTagValueNodes[0] ?? null;
    }

    /**
     * @api used in tests, doctrine
     */
    public function hasByAnnotationClass(string $class): bool
    {
        return $this->findByAnnotationClass($class) !== [];
    }

    /**
     * @param string[] $annotationsClasses
     */
    public function hasByAnnotationClasses(array $annotationsClasses): bool
    {
        return $this->getByAnnotationClasses($annotationsClasses) instanceof DoctrineAnnotationTagValueNode;
    }

    public function findOneByAnnotationClass(string $desiredClass): ?DoctrineAnnotationTagValueNode
    {
        $foundTagValueNodes = $this->findByAnnotationClass($desiredClass);
        return $foundTagValueNodes[0] ?? null;
    }

    /**
     * @template T of \PHPStan\PhpDocParser\Ast\Node
     * @param class-string<T> $typeToRemove
     */
    public function removeByType(string $typeToRemove, ?string $name = null): bool
    {
        $hasChanged = false;

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable($this->phpDocNode, '', static function (Node $node) use (
            $typeToRemove,
            &$hasChanged,
            $name
        ): ?int {
            if ($node instanceof PhpDocTagNode && $node->value instanceof $typeToRemove) {
                // keep special annotation for tools
                if (str_starts_with($node->name, '@psalm-')) {
                    return null;
                }

                if (str_starts_with($node->name, '@phpstan-')) {
                    return null;
                }

                if ($name !== null && $node->value instanceof VarTagValueNode && $node->value->variableName !== '$' . ltrim(
                    $name,
                    '$'
                )) {
                    return PhpDocNodeTraverser::DONT_TRAVERSE_CHILDREN;
                }

                $hasChanged = true;
                return PhpDocNodeTraverser::NODE_REMOVE;
            }

            if (! $node instanceof $typeToRemove) {
                return null;
            }

            $hasChanged = true;
            return PhpDocNodeTraverser::NODE_REMOVE;
        });

        return $hasChanged;
    }

    public function removeByName(string $tagName): bool
    {
        $tagName = '@' . ltrim($tagName, '@');
        $hasChanged = false;

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable($this->phpDocNode, '', static function (Node $node) use (
            $tagName,
            &$hasChanged
        ): ?int {
            if ($node instanceof PhpDocTagNode && $node->name === $tagName) {
                $hasChanged = true;
                return PhpDocNodeTraverser::NODE_REMOVE;
            }

            return null;
        });

        return $hasChanged;
    }

    public function addTagValueNode(PhpDocTagValueNode $phpDocTagValueNode): void
    {
        if ($phpDocTagValueNode instanceof DoctrineAnnotationTagValueNode) {
            if ($phpDocTagValueNode->identifierTypeNode instanceof ShortenedIdentifierTypeNode) {
                $name = '@' . $phpDocTagValueNode->identifierTypeNode;
            } else {
                $name = '@\\' . $phpDocTagValueNode->identifierTypeNode;
            }

            $spacelessPhpDocTagNode = new SpacelessPhpDocTagNode($name, $phpDocTagValueNode);
            $this->addPhpDocTagNode($spacelessPhpDocTagNode);
            return;
        }

        $name = $this->resolveNameForPhpDocTagValueNode($phpDocTagValueNode);
        if (! is_string($name)) {
            throw new ShouldNotHappenException(sprintf(
                'Name could not be resolved for "%s" tag value node. Complete it to %s::TAGS_TYPES_TO_NAMES constant',
                $phpDocTagValueNode::class,
                self::class,
            ));
        }

        $phpDocTagNode = new PhpDocTagNode($name, $phpDocTagValueNode);
        $this->addPhpDocTagNode($phpDocTagNode);
    }

    public function isNewNode(): bool
    {
        if ($this->phpDocNode->children === []) {
            return false;
        }

        return $this->betterTokenIterator->count() === 0;
    }

    public function isSingleLine(): bool
    {
        return $this->isSingleLine;
    }

    public function hasInvalidTag(string $name): bool
    {
        // fallback for invalid tag value node
        foreach ($this->phpDocNode->children as $phpDocChildNode) {
            if (! $phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }

            if ($phpDocChildNode->name !== $name) {
                continue;
            }

            if (! $phpDocChildNode->value instanceof InvalidTagValueNode) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function getReturnTagValue(): ?ReturnTagValueNode
    {
        $returnTagValueNodes = $this->phpDocNode->getReturnTagValues();
        return $returnTagValueNodes[0] ?? null;
    }

    public function getParamTagValueByName(string $name): ?ParamTagValueNode
    {
        $desiredParamNameWithDollar = '$' . ltrim($name, '$');

        foreach ($this->getParamTagValueNodes() as $paramTagValueNode) {
            if ($paramTagValueNode->parameterName !== $desiredParamNameWithDollar) {
                continue;
            }

            return $paramTagValueNode;
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getTemplateNames(): array
    {
        $templateNames = [];
        foreach ($this->phpDocNode->getTemplateTagValues() as $templateTagValueNode) {
            $templateNames[] = $templateTagValueNode->name;
        }

        return $templateNames;
    }

    public function makeMultiLined(): void
    {
        $this->isSingleLine = false;
    }

    public function getNode(): \PhpParser\Node
    {
        return $this->node;
    }

    /**
     * @return string[]
     */
    public function getAnnotationClassNames(): array
    {
        /** @var IdentifierTypeNode[] $identifierTypeNodes */
        $identifierTypeNodes = $this->phpDocNodeByTypeFinder->findByType($this->phpDocNode, IdentifierTypeNode::class);

        $resolvedClasses = [];

        foreach ($identifierTypeNodes as $identifierTypeNode) {
            $resolvedClasses[] = ltrim($identifierTypeNode->name, '@');
        }

        return $resolvedClasses;
    }

    /**
     * @return string[]
     */
    public function getGenericTagClassNames(): array
    {
        /** @var GenericTagValueNode[] $genericTagValueNodes */
        $genericTagValueNodes = $this->phpDocNodeByTypeFinder->findByType(
            $this->phpDocNode,
            GenericTagValueNode::class
        );

        $resolvedClasses = [];

        foreach ($genericTagValueNodes as $genericTagValueNode) {
            if ($genericTagValueNode->value !== '') {
                $resolvedClasses[] = $genericTagValueNode->value;
            }
        }

        return $resolvedClasses;
    }

    /**
     * @return string[]
     */
    public function getConstFetchNodeClassNames(): array
    {
        $phpDocNodeTraverser = new PhpDocNodeTraverser();

        $classNames = [];

        $phpDocNodeTraverser->traverseWithCallable($this->phpDocNode, '', static function (Node $node) use (
            &$classNames,
        ): ?ConstTypeNode {
            if (! $node instanceof ConstTypeNode) {
                return null;
            }

            if (! $node->constExpr instanceof ConstFetchNode) {
                return null;
            }

            $classNames[] = $node->constExpr->getAttribute(PhpDocAttributeKey::RESOLVED_CLASS);
            return $node;
        });

        return $classNames;
    }

    /**
     * @return string[]
     */
    public function getArrayItemNodeClassNames(): array
    {
        $phpDocNodeTraverser = new PhpDocNodeTraverser();

        $classNames = [];

        $phpDocNodeTraverser->traverseWithCallable($this->phpDocNode, '', static function (Node $node) use (
            &$classNames,
        ): ?ArrayItemNode {
            if (! $node instanceof ArrayItemNode) {
                return null;
            }

            $resolvedClass = $node->getAttribute(PhpDocAttributeKey::RESOLVED_CLASS);
            if ($resolvedClass === null) {
                return null;
            }

            $classNames[] = $resolvedClass;
            return $node;
        });

        return $classNames;
    }

    /**
     * @param class-string $desiredClass
     * @return DoctrineAnnotationTagValueNode[]
     */
    public function findByAnnotationClass(string $desiredClass): array
    {
        return $this->phpDocNodeByTypeFinder->findDoctrineAnnotationsByClass($this->phpDocNode, $desiredClass);
    }

    private function resolveNameForPhpDocTagValueNode(PhpDocTagValueNode $phpDocTagValueNode): ?string
    {
        foreach (self::TAGS_TYPES_TO_NAMES as $tagValueNodeType => $name) {
            /** @var class-string<PhpDocTagValueNode> $tagValueNodeType */
            if ($phpDocTagValueNode instanceof $tagValueNodeType) {
                return $name;
            }
        }

        return null;
    }

    private function getTypeOrMixed(?PhpDocTagValueNode $phpDocTagValueNode): MixedType | Type
    {
        if (! $phpDocTagValueNode instanceof PhpDocTagValueNode) {
            return new MixedType();
        }

        return $this->staticTypeMapper->mapPHPStanPhpDocTypeToPHPStanType($phpDocTagValueNode, $this->node);
    }
}
