<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\NodeAnalyzer\TerminatedNodeAnalyzer;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\FunctionLike\TooWideReturnTypeRector\TooWideReturnTypeRectorTest
 */
final class TooWideReturnTypeRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly TerminatedNodeAnalyzer $terminatedNodeAnalyzer
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
        $scope = ScopeFetcher::fetch($node);

        if ($this->shouldSkipNode($node, $scope)) {
            return null;
        }

        $returnStatements = $this->betterNodeFinder->findInstanceOf($node, Return_::class);
        $isAlwaysTerminating = $node instanceof ArrowFunction
            || $this->terminatedNodeAnalyzer->isAlwaysTerminating($node);

        if ($returnStatements === [] && ! $node instanceof ArrowFunction) {
            $node->returnType = $isAlwaysTerminating
                ? new Identifier('never')
                : new Identifier('void');

            return $node;
        }

        $returnType = $node->returnType;

        if (! $returnType instanceof UnionType) {
            return null;
        }

        $actualReturnTypes = $this->collectActualReturnTypes($node, $returnStatements, $isAlwaysTerminating);
        $newReturnType = $this->narrowUnionReturnType($returnType, $actualReturnTypes);

        if ($newReturnType === null) {
            return null;
        }

        $node->returnType = $newReturnType;

        return $node;
    }

    private function shouldSkipNode(ClassMethod|Function_|Closure|ArrowFunction $node, Scope $scope): bool
    {
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

        return ! $classReflection->isFinal();
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
            return [$this->getType($node->expr)];
        }

        $returnTypes = [];
        foreach ($returnStatements as $returnStatement) {
            if ($returnStatement->expr === null) {
                $returnTypes[] = new NullType();
                continue;
            }

            $returnTypes[] = $this->getType($returnStatement->expr);
        }

        if (! $isAlwaysTerminating) {
            $returnTypes[] = new NullType();
        }

        return $returnTypes;
    }

    /**
     * @param Type[] $actualReturnTypes
     */
    private function narrowUnionReturnType(
        UnionType $unionType,
        array $actualReturnTypes
    ): ComplexType|Identifier|Name|null {
        $types = $unionType->types;
        $usedTypes = [];

        foreach ($types as $type) {
            $declaredType = $this->getType($type);
            if ($declaredType instanceof MixedType) {
                // Mixed type covers all other types, so we should only keep mixed
                return new Identifier('mixed');
            }
            foreach ($actualReturnTypes as $actualType) {
                if (! $declaredType->isSuperTypeOf($actualType)->no()) {
                    $usedTypes[] = $declaredType;
                    break;
                }
            }
        }

        if ($usedTypes === [] || count($usedTypes) === count($types)) {
            return null;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            TypeCombinator::union(...$usedTypes),
            TypeKind::RETURN
        );
    }

    private function hasYield(ClassMethod|Function_|Closure|ArrowFunction $node): bool
    {
        if ($node instanceof ArrowFunction) {
            return false;
        }

        $stmts = $node->stmts;

        if ($stmts === null || $stmts === []) {
            return false;
        }

        return (bool) $this->betterNodeFinder->findFirst(
            $stmts,
            fn (Node $subNode): bool => $subNode instanceof Yield_ || $subNode instanceof YieldFrom
        );
    }
}
