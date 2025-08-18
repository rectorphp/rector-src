<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType as PHPStanUnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
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
        private readonly TypeFactory $typeFactory
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

        if ($this->shouldSkipByDocblock($node)) {
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

        if (! $newReturnType instanceof Node) {
            return null;
        }

        // mostly placeholder
        if ($this->isName($newReturnType, 'null')) {
            return null;
        }

        $node->returnType = $newReturnType;

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);

        if ($phpDocInfo?->hasByName('@return') === true) {
            $this->changePhpDocReturnType($node, $phpDocInfo, $unusedTypes);
        }

        return $node;
    }

    private function shouldSkipByDocblock(ClassMethod|Function_|Closure|ArrowFunction $node): bool
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);

        if (! $phpDocInfo instanceof PhpDocInfo) {
            return false;
        }

        $returnTag = $phpDocInfo->getReturnTagValue();

        if (! $returnTag instanceof ReturnTagValueNode) {
            return false;
        }

        $returnType = $phpDocInfo->getReturnType();
        if (! $returnType instanceof \PHPStan\Type\UnionType) {
            return false;
        }

        $type = $this->typeFactory->createMixedPassedOrUnionType($returnType->getTypes());
        return ! $type->equals($returnType);
    }

    private function shouldSkipNode(ClassMethod|Function_|Closure|ArrowFunction $node): bool
    {
        $returnType = $node->returnType;

        if (! $returnType instanceof UnionType && ! $returnType instanceof NullableType) {
            return true;
        }

        $types = $returnType instanceof UnionType
            ? $returnType->types
            : [new ConstFetch(new Name('null')), $returnType->type];

        foreach ($types as $type) {
            if ($this->isNames($type, ['true', 'false'])) {
                return true;
            }
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
    private function collectActualReturnTypes(array $returnStatements): array
    {
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

    /**
     * @param Type[] $unusedTypes
     */
    private function changePhpDocReturnType(
        FunctionLike $functionLike,
        PhpDocInfo $phpDocInfo,
        array $unusedTypes,
    ): void {
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();

        if (! $returnTagValueNode instanceof ReturnTagValueNode) {
            return;
        }

        $newReturnType = TypeCombinator::remove($phpDocInfo->getReturnType(), TypeCombinator::union(...$unusedTypes));
        $this->phpDocTypeChanger->changeReturnType($functionLike, $phpDocInfo, $newReturnType);
    }
}
