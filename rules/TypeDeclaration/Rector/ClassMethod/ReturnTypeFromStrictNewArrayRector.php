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
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersion;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
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
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly ReturnTypeInferer $returnTypeInferer
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
        if ($returns === []) {
            return null;
        }

        if ($this->isVariableOverriddenWithNonArray($node, $variable)) {
            return null;
        }

        if (count($returns) > 1) {
            $returnType = $this->returnTypeInferer->inferFunctionLike($node);
            return $this->processAddArrayReturnType($node, $returnType);
        }

        $onlyReturn = $returns[0];
        if (! $onlyReturn->expr instanceof Variable) {
            return null;
        }

        $returnType = $this->nodeTypeResolver->getNativeType($onlyReturn->expr);
        return $this->processAddArrayReturnType($node, $returnType);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }

    private function processAddArrayReturnType(
        ClassMethod|Function_|Closure $node,
        Type $returnType
    ): ClassMethod|Function_|Closure|null {
        if (! $returnType->isArray()->yes()) {
            return null;
        }

        // always returns array
        $node->returnType = new Identifier('array');

        // add more precise array type if suitable
        if ($returnType instanceof ArrayType && $this->shouldAddReturnArrayDocType($returnType)) {
            $this->changeReturnType($node, $returnType);
        }

        return $node;
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

    private function changeReturnType(ClassMethod|Function_|Closure $node, ArrayType $arrayType): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        // skip already filled type, on purpose
        if (! $phpDocInfo->getReturnType() instanceof MixedType) {
            return;
        }

        // can handle only exactly 1-type array
        if ($arrayType instanceof ConstantArrayType && count($arrayType->getValueTypes()) !== 1) {
            return;
        }

        $narrowArrayType = new ArrayType(new MixedType(), $arrayType->getItemType());
        $this->phpDocTypeChanger->changeReturnType($node, $phpDocInfo, $narrowArrayType);
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

            if ($assign->expr instanceof Array_) {
                continue;
            }

            $nativeType = $this->nodeTypeResolver->getNativeType($assign->expr);
            if (! $nativeType->isArray()->yes()) {
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

            $nativeType = $this->nodeTypeResolver->getNativeType($assign->expr);
            if ($nativeType->isArray()->yes()) {
                return $assign->var;
            }
        }

        return null;
    }

    private function shouldAddReturnArrayDocType(ArrayType $arrayType): bool
    {
        if ($arrayType instanceof ConstantArrayType) {
            if ($arrayType->getItemType() instanceof NeverType) {
                return false;
            }

            // handle only simple arrays
            if (! $arrayType->getKeyType() instanceof IntegerType) {
                return false;
            }
        }

        return true;
    }
}
