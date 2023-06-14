<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersion;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\ReturnTypeFromStrictNewArrayRectorTest
 */
final class ReturnTypeFromStrictNewArrayRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly TypeComparator $typeComparator,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add strict return array type based on created empty array and returned', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $values = [];

        return $values;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): array
    {
        $values = [];

        return $values;
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
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($this->shouldSkip($node, $scope)) {
            return null;
        }

        // 1. is variable instantiated with array
        $stmts = $node->stmts;
        if ($stmts === null) {
            return null;
        }

        $variable = $this->matchArrayAssignedVariable($stmts);
        if (! $variable instanceof Variable) {
            return null;
        }

        // 2. skip yields
        if ($this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped($node, [Yield_::class])) {
            return null;
        }

        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($node, Return_::class);
        if (count($returns) !== 1) {
            return null;
        }

        if ($this->isVariableOverriddenWithNonArray($node, $variable)) {
            return null;
        }

        $onlyReturn = $returns[0];
        if (! $onlyReturn->expr instanceof Variable) {
            return null;
        }

        $returnType = $this->nodeTypeResolver->getType($onlyReturn->expr);

        if (! $returnType->isArray()->yes()) {
            return null;
        }

        if (! $this->nodeNameResolver->areNamesEqual($onlyReturn->expr, $variable)) {
            return null;
        }

        // 3. always returns array
        $node->returnType = new Identifier('array');

        // 4. add more precise type if suitable
        $exprType = $this->getType($onlyReturn->expr);

        if ($this->shouldAddReturnArrayDocType($exprType)) {
            $this->changeReturnType($node, $exprType);
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }

    private function shouldSkip(ClassMethod|Function_|Closure $node, Scope $scope): bool
    {
        if ($node->returnType !== null) {
            return true;
        }

        return $node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        );
    }

    private function changeReturnType(ClassMethod|Function_|Closure $node, Type $exprType): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $exprType = $this->narrowConstantArrayType($exprType);

        if (! $this->typeComparator->isSubtype($phpDocInfo->getReturnType(), $exprType)) {
            $this->phpDocTypeChanger->changeReturnType($node, $phpDocInfo, $exprType);
        }
    }

    private function isVariableOverriddenWithNonArray(
        ClassMethod|Function_|Closure $functionLike,
        Variable $variable
    ): bool {
        // is variable overriden?
        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Assign::class);
        foreach ($assigns as $assign) {
            if (! $assign->var instanceof Variable) {
                continue;
            }

            if (! $this->nodeNameResolver->areNamesEqual($assign->var, $variable)) {
                continue;
            }

            if (! $assign->expr instanceof Array_) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Stmt[] $stmts
     */
    private function matchArrayAssignedVariable(array $stmts): Variable|null
    {
        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;
            if (! $assign->var instanceof Variable) {
                continue;
            }

            if (! $assign->expr instanceof Array_) {
                continue;
            }

            return $assign->var;
        }

        return null;
    }

    private function shouldAddReturnArrayDocType(Type $exprType): bool
    {
        if ($exprType instanceof ConstantArrayType) {
            // sign of empty array, keep empty
            return ! $exprType->getItemType() instanceof NeverType;
        }

        return $exprType->isArray()
            ->yes();
    }

    private function narrowConstantArrayType(Type $type): Type
    {
        if (! $type instanceof ConstantArrayType) {
            return $type;
        }

        if (count($type->getValueTypes()) === 1) {
            $singleValueType = $type->getValueTypes()[0];
            if ($singleValueType instanceof ObjectType) {
                return $type;
            }
        }

        $printedDescription = $type->describe(VerbosityLevel::precise());
        if (strlen($printedDescription) > 50) {
            return new ArrayType(new MixedType(), new MixedType());
        }

        return $type;
    }
}
