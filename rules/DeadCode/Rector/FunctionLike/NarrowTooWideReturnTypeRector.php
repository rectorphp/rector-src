<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\FunctionLike;

use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType as PHPStanUnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\NarrowTooWideReturnTypeRectorTest
 */
final class NarrowTooWideReturnTypeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly SilentVoidResolver $silentVoidResolver,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly TypeFactory $typeFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Narrow too wide return type declarations if possible',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function foo(): string|int|\DateTime
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 1000;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function foo(): string|int
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 1000;
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULLABLE_TYPE;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class, Closure::class, ArrowFunction::class];
    }

    /**
     * @param ClassMethod|Function_|Closure|ArrowFunction $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipNode($node)) {
            return null;
        }

        $returnStatements = $this->betterNodeFinder->findReturnsScoped($node);

        if ($returnStatements === []) {
            return null;
        }

        $hasImplicitNullReturn = $this->silentVoidResolver->hasSilentVoid($node)
            || $this->hasImplicitNullReturn($returnStatements);

        $returnType = $node->returnType;
        Assert::isInstanceOfAny($returnType, [UnionType::class, NullableType::class]);

        $returnType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($returnType);
        $actualReturnTypes = $this->collectActualReturnTypes($returnStatements);

        if ($hasImplicitNullReturn) {
            $actualReturnTypes[] = new NullType();
        }

        $unusedTypes = $this->getUnusedType($returnType, $actualReturnTypes);

        if ($unusedTypes === []) {
            return null;
        }

        $newReturnType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            TypeCombinator::remove($returnType, TypeCombinator::union(...$unusedTypes)),
            TypeKind::RETURN
        );

        if ($newReturnType === null) {
            return null;
        }

        $node->returnType = $newReturnType;

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);

        if ($phpDocInfo?->hasByName('@return')) {
            $this->changePhpDocReturnType($node, $phpDocInfo, $unusedTypes);
        }

        return $node;
    }

    private function shouldSkipNode(ClassMethod|Function_|Closure|ArrowFunction $node): bool
    {
        $returnType = $node->returnType;

        if (! $returnType instanceof UnionType && ! $returnType instanceof NullableType) {
            return true;
        }

        if ($this->hasYield($node)) {
            return true;
        }

        if (! $node instanceof ClassMethod) {
            return false;
        }

        if ($node->isPrivate() || $node->isFinal()) {
            return false;
        }

        if ($node->isAbstract()) {
            return true;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);

        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        if (! $classReflection->isClass()) {
            return true;
        }

        return ! $classReflection->isFinalByKeyword();
    }

    /**
     * @param Return_[] $returnStatements
     */
    private function hasImplicitNullReturn(array $returnStatements): bool
    {
        foreach ($returnStatements as $returnStatement) {
            if ($returnStatement->expr === null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Return_[] $returnStatements
     * @return Type[]
     */
    private function collectActualReturnTypes(array $returnStatements): array {
        $returnTypes = [];

        foreach ($returnStatements as $returnStatement) {
            if ($returnStatement->expr === null) {
                continue;
            }

            $returnTypes[] = $this->nodeTypeResolver->getNativeType($returnStatement->expr);
        }

        return $returnTypes;
    }

    /**
     * @param Type[] $actualReturnTypes
     * @return Type[]
     */
    private function getUnusedType(Type $returnType, array $actualReturnTypes): array
    {
        $types = $returnType instanceof PHPStanUnionType ? $returnType->getTypes() : [$returnType];
        $unusedTypes = [];

        foreach ($types as $type) {
            foreach ($actualReturnTypes as $actualReturnType) {
                if (! $type->isSuperTypeOf($actualReturnType)->no()) {
                    continue 2;
                }
            }

            $unusedTypes[] = $type;
        }

        return $unusedTypes;
    }

    private function hasYield(FunctionLike $node): bool
    {
        return $this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped(
            $node,
            [Yield_::class, YieldFrom::class]
        );
    }

    /**
     * @param Type[] $unusedTypes
     */
    private function changePhpDocReturnType(
        FunctionLike $node,
        PhpDocInfo $phpDocInfo,
        array $unusedTypes,
    ): void {
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();

        if (! $returnTagValueNode instanceof ReturnTagValueNode) {
            return;
        }

        $typeNode = $returnTagValueNode->type;

        // Skip wildcards like CiDetector::CI_* as they are resolved to
        // actual constant types like 'GitHubActions' or 'GitLabCI' and
        // we can't replace the type while preserving the `CI_*` portion
        if (preg_match('/::[_A-Z]*\*/', (string) $returnTagValueNode->type)) {
            return;
        }

        $newReturnType = TypeCombinator::remove($phpDocInfo->getReturnType(), TypeCombinator::union(...$unusedTypes));

        $this->phpDocTypeChanger->changeReturnType($node, $phpDocInfo, $newReturnType);
    }
}
