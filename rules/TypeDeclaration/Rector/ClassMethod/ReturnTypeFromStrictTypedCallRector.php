<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType as PhpParserUnionType;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;
use Rector\Php\PhpVersionProvider;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\TypeDeclaration\NodeAnalyzer\TypeNodeUnwrapper;
use Rector\TypeDeclaration\TypeAnalyzer\ReturnStrictTypeAnalyzer;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\ReturnTypeFromStrictTypedCallRectorTest
 */
final class ReturnTypeFromStrictTypedCallRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly TypeNodeUnwrapper $typeNodeUnwrapper,
        private readonly ReturnStrictTypeAnalyzer $returnStrictTypeAnalyzer,
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return type from strict return type of call', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function getData()
    {
        return $this->getNumber();
    }

    private function getNumber(): int
    {
        return 1000;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function getData(): int
    {
        return $this->getNumber();
    }

    private function getNumber(): int
    {
        return 1000;
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
        return [ClassMethod::class, Function_::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        if ($this->shouldSkip($node, $scope)) {
            return null;
        }

        $currentScopeReturns = $this->findCurrentScopeReturns($node);

        $returnedStrictTypes = $this->returnStrictTypeAnalyzer->collectStrictReturnTypes($currentScopeReturns, $scope);
        if ($returnedStrictTypes === []) {
            return null;
        }

        if (count($returnedStrictTypes) === 1) {
            return $this->refactorSingleReturnType($currentScopeReturns[0], $returnedStrictTypes[0], $node);
        }

        if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            /** @var PhpParserUnionType[] $returnedStrictTypes */
            $unwrappedTypes = $this->typeNodeUnwrapper->unwrapNullableUnionTypes($returnedStrictTypes);
            $node->returnType = new PhpParserUnionType($unwrappedTypes);

            return $node;
        }

        return null;
    }

    private function isUnionPossibleReturnsVoid(ClassMethod | Function_ $node): bool
    {
        $inferReturnType = $this->returnTypeInferer->inferFunctionLike($node);
        if ($inferReturnType instanceof UnionType) {
            foreach ($inferReturnType->getTypes() as $type) {
                if ($type->isVoid()->yes()) {
                    return true;
                }
            }
        }

        return false;
    }

    private function processSingleUnionType(
        ClassMethod | Function_ $node,
        UnionType $unionType,
        NullableType $nullableType
    ): ClassMethod | Function_ {
        $types = $unionType->getTypes();
        $returnType = $types[0] instanceof ObjectType && $types[1] instanceof NullType
            ? new NullableType(new FullyQualified($types[0]->getClassName()))
            : $nullableType;

        $node->returnType = $returnType;
        return $node;
    }

    private function shouldSkip(ClassMethod | Function_ $node, Scope $scope): bool
    {
        if ($node->returnType !== null) {
            return true;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return true;
        }

        return $this->isUnionPossibleReturnsVoid($node);
    }

    private function refactorSingleReturnType(
        Return_ $return,
        Identifier|Name|NullableType|ComplexType $returnedStrictTypeNode,
        ClassMethod | Function_ $functionLike
    ): ClassMethod | Function_ {
        $resolvedType = $this->nodeTypeResolver->getType($return);

        if ($resolvedType instanceof UnionType) {
            if (! $returnedStrictTypeNode instanceof NullableType) {
                return $functionLike;
            }

            return $this->processSingleUnionType($functionLike, $resolvedType, $returnedStrictTypeNode);
        }

        /** @var Name $returnType */
        $returnType = $resolvedType instanceof ObjectType
            ? new FullyQualified($resolvedType->getClassName())
            : $returnedStrictTypeNode;

        $functionLike->returnType = $returnType;

        return $functionLike;
    }

    /**
     * @return Return_[]
     */
    private function findCurrentScopeReturns(ClassMethod|Function_ $node): array
    {
        $currentScopeReturns = [];

        if ($node->stmts === null) {
            return [];
        }

        $this->traverseNodesWithCallable($node->stmts, static function (Node $node) use (&$currentScopeReturns): ?int {
            // skip scope nesting
            if ($node instanceof FunctionLike) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node instanceof Return_) {
                return null;
            }

            if (! $node->expr instanceof Expr) {
                return null;
            }

            $currentScopeReturns[] = $node;
            return null;
        });
        return $currentScopeReturns;
    }
}
