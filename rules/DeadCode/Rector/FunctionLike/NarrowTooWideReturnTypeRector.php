<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
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
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
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

        $returnStatements = $node instanceof ArrowFunction
            ? []
            : $this->betterNodeFinder->findReturnsScoped($node);
        $isAlwaysTerminating = ! $this->silentVoidResolver->hasSilentVoid($node);

        if ($returnStatements === [] && ! $node instanceof ArrowFunction) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        $hasReturnDocblock = (bool) $phpDocInfo?->hasByName('@return');

        if ($hasReturnDocblock) {
            $returnType = $phpDocInfo->getReturnType();
        } else {
            $returnType = $node->returnType;
            Assert::isInstanceOfAny($returnType, [UnionType::class, NullableType::class]);
            $returnType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($returnType);
        }

        $actualReturnTypes = $this->collectActualReturnTypes($node, $returnStatements, $isAlwaysTerminating);
        $newReturnType = $this->narrowReturnType($returnType, $actualReturnTypes);

        if (! $newReturnType instanceof Type) {
            return null;
        }

        $node->returnType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            $newReturnType,
            TypeKind::RETURN
        );

        if ($hasReturnDocblock) {
            $this->phpDocTypeChanger->changeReturnType($node, $phpDocInfo, $newReturnType);
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
     * @return Type[]
     */
    private function collectActualReturnTypes(
        ClassMethod|Function_|Closure|ArrowFunction $node,
        array $returnStatements,
        bool $isAlwaysTerminating,
    ): array {
        if ($node instanceof ArrowFunction) {
            return [$this->nodeTypeResolver->getNativeType($node->expr)];
        }

        $returnTypes = [];
        foreach ($returnStatements as $returnStatement) {
            if ($returnStatement->expr === null) {
                $returnTypes[] = new NullType();
                continue;
            }

            $returnTypes[] = $this->nodeTypeResolver->getNativeType($returnStatement->expr);
        }

        if (! $isAlwaysTerminating) {
            $returnTypes[] = new NullType();
        }

        return $returnTypes;
    }

    /**
     * @param Type[] $actualReturnTypes
     */
    private function narrowReturnType(Type $returnType, array $actualReturnTypes): Type|null
    {
        $types = $returnType instanceof PHPStanUnionType ? $returnType->getTypes() : [$returnType];
        $usedTypes = [];

        foreach ($types as $type) {
            foreach ($actualReturnTypes as $actualReturnType) {
                if (! $type->isSuperTypeOf($actualReturnType)->no()) {
                    $usedTypes[] = $type;
                    break;
                }
            }
        }

        $usedTypes = array_unique($usedTypes, SORT_REGULAR);

        if ($usedTypes === [] || count($usedTypes) === count($types)) {
            return null;
        }

        return TypeCombinator::union(...$usedTypes);
    }

    private function hasYield(ClassMethod|Function_|Closure|ArrowFunction $node): bool
    {
        if ($node instanceof ArrowFunction) {
            return false;
        }

        return $this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped(
            $node,
            [Yield_::class, YieldFrom::class]
        );
    }
}
