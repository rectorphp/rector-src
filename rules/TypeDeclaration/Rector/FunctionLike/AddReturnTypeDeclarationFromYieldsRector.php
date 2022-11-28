<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedGenericObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\AddReturnTypeDeclarationFromYieldsRectorTest
 */
final class AddReturnTypeDeclarationFromYieldsRector extends AbstractRector
{
    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return type declarations from yields', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function provide()
    {
        yield 1;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return Iterator<int>
     */
    public function provide(): Iterator
    {
        yield 1;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Function_::class, ClassMethod::class];
    }

    /**
     * @param Function_|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $yieldNodes = $this->findCurrentScopeYieldNodes($node);
        if ($yieldNodes === []) {
            return null;
        }

        // skip already filled type
        if ($node->returnType instanceof Node && $this->isNames(
            $node->returnType,
            ['Iterator', 'Generator', 'Traversable']
        )) {
            return null;
        }

        $yieldType = $this->resolveYieldType($yieldNodes, $node);
        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($yieldType, TypeKind::RETURN);
        if (! $returnTypeNode instanceof Node) {
            return null;
        }

        $node->returnType = $returnTypeNode;
        return $node;
    }

    /**
     * @return Yield_[]|YieldFrom[]
     */
    private function findCurrentScopeYieldNodes(FunctionLike $functionLike): array
    {
        $yieldNodes = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $functionLike->getStmts(), static function (
            Node $node
        ) use (&$yieldNodes): ?int {
            // skip anonymous class and inner function
            if ($node instanceof Class_) {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            // skip nested scope
            if ($node instanceof FunctionLike) {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            if (! $node instanceof Yield_ && ! $node instanceof YieldFrom) {
                return null;
            }

            $yieldNodes[] = $node;
            return null;
        });

        return $yieldNodes;
    }

    private function resolveYieldValue(Yield_ | YieldFrom $yield): ?Expr
    {
        if ($yield instanceof Yield_) {
            return $yield->value;
        }

        return $yield->expr;
    }

    /**
     * @param array<Yield_|YieldFrom> $yieldNodes
     * @return Type[]
     */
    private function resolveYieldedTypes(array $yieldNodes): array
    {
        $yieldedTypes = [];

        foreach ($yieldNodes as $yieldNode) {
            $value = $this->resolveYieldValue($yieldNode);
            if (! $value instanceof Expr) {
                // one of the yields is empty
                return [];
            }

            $resolvedType = $this->nodeTypeResolver->getType($value);
            if ($resolvedType instanceof MixedType) {
                continue;
            }

            $yieldedTypes[] = $resolvedType;
        }

        return $yieldedTypes;
    }

    /**
     * @param array<Yield_|YieldFrom> $yieldNodes
     */
    private function resolveYieldType(
        array $yieldNodes,
        ClassMethod|Function_ $functionLike
    ): FullyQualifiedObjectType|FullyQualifiedGenericObjectType {
        $yieldedTypes = $this->resolveYieldedTypes($yieldNodes);

        $className = $this->resolveClassName($functionLike);

        if ($yieldedTypes === []) {
            return new FullyQualifiedObjectType($className);
        }

        $yieldedTypes = $this->typeFactory->createMixedPassedOrUnionType($yieldedTypes);
        return new FullyQualifiedGenericObjectType($className, [$yieldedTypes]);
    }

    private function resolveClassName(Function_|ClassMethod $functionLike): string
    {
        $returnTypeNode = $functionLike->getReturnType();

        if ($returnTypeNode instanceof Identifier && $returnTypeNode->name === 'iterable') {
            return 'Iterator';
        }

        if ($returnTypeNode instanceof Name && ! $this->nodeNameResolver->isName($returnTypeNode, 'Generator')) {
            return $this->nodeNameResolver->getName($returnTypeNode);
        }

        return 'Generator';
    }
}
