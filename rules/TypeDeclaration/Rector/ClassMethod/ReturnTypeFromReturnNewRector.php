<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Enum\ObjectReference;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeTypeResolver\NodeTypeResolver\NewTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\SelfStaticType;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer\StrictReturnNewAnalyzer;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\ReturnTypeFromReturnNewRectorTest
 */
final class ReturnTypeFromReturnNewRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly StrictReturnNewAnalyzer $strictReturnNewAnalyzer,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly NewTypeResolver $newTypeResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return type to function like with return new', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function action()
    {
        return new Response();
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function action(): Response
    {
        return new Response();
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
        return [ClassMethod::class, Function_::class, Closure::class, ArrowFunction::class];
    }

    /**
     * @param ClassMethod|Function_|ArrowFunction $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node->returnType !== null) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return null;
        }

        if (! $node instanceof ArrowFunction) {
            $returnedNewClassName = $this->strictReturnNewAnalyzer->matchAlwaysReturnVariableNew($node);
            if (is_string($returnedNewClassName)) {
                $node->returnType = new FullyQualified($returnedNewClassName);

                return $node;
            }
        }

        return $this->refactorDirectReturnNew($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }

    private function createObjectTypeFromNew(New_ $new): ObjectType|ObjectWithoutClassType|StaticType|null
    {
        if ($this->classAnalyzer->isAnonymousClass($new->class)) {
            $newType = $this->newTypeResolver->resolve($new);
            if (! $newType instanceof ObjectWithoutClassType) {
                return null;
            }

            return $newType;
        }

        if (! $new->class instanceof Name) {
            return null;
        }

        $className = $this->getName($new->class);
        if ($className === ObjectReference::STATIC || $className === ObjectReference::SELF) {
            $classReflection = $this->reflectionResolver->resolveClassReflection($new);
            if (! $classReflection instanceof ClassReflection) {
                throw new ShouldNotHappenException();
            }

            if ($className === ObjectReference::SELF) {
                return new SelfStaticType($classReflection);
            }

            return new StaticType($classReflection);
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        return new ObjectType($className, null, $classReflection);
    }

    private function refactorDirectReturnNew(
        ClassMethod|Function_|ArrowFunction|Closure $node
    ): null|ArrowFunction|Function_|ClassMethod|Closure {
        if ($node instanceof ArrowFunction) {
            $returns = [new Return_($node->expr)];
        } else {
            /** @var Return_[] $returns */
            $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($node, Return_::class);
        }

        if ($returns === []) {
            return null;
        }

        $newTypes = $this->resolveReturnNewType($returns);
        if ($newTypes === null) {
            return null;
        }

        $returnType = $this->typeFactory->createMixedPassedOrUnionType($newTypes);

        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
        if (! $returnTypeNode instanceof Node) {
            return null;
        }

        $returnType = $this->returnTypeInferer->inferFunctionLike($node);
        if ($returnType instanceof UnionType) {
            return null;
        }

        $node->returnType = $returnTypeNode;

        return $node;
    }

    /**
     * @param Return_[] $returns
     * @return Type[]|null
     */
    private function resolveReturnNewType(array $returns): ?array
    {
        $newTypes = [];
        foreach ($returns as $return) {
            if (! $return->expr instanceof New_) {
                return null;
            }

            $newType = $this->createObjectTypeFromNew($return->expr);
            if (! $newType instanceof Type) {
                return null;
            }

            $newTypes[] = $newType;
        }

        return $newTypes;
    }
}
