<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector\PrivateMethodReturnTypeFromStrictNewArrayRectorTest
 */
final class PrivateMethodReturnTypeFromStrictNewArrayRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ReturnAnalyzer $returnAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add strict return array type to private methods based on created empty array and returned',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    private function run()
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
    private function run(): array
    {
        $values = [];

        return $values;
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        // 1. is variable instantiated with array
        $stmts = $node->stmts;
        if ($stmts === null) {
            return null;
        }

        $variables = $this->matchArrayAssignedVariable($stmts);
        if ($variables === []) {
            return null;
        }

        $returns = $this->betterNodeFinder->findReturnsScoped($node);
        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($node, $returns)) {
            return null;
        }

        $variables = $this->matchVariableNotOverriddenByNonArray($node, $variables);
        if ($variables === []) {
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

        if (! $this->nodeComparator->isNodeEqual($onlyReturn->expr, $variables)) {
            return null;
        }

        $returnType = $this->nodeTypeResolver->getNativeType($onlyReturn->expr);
        return $this->processAddArrayReturnType($node, $returnType);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }

    private function processAddArrayReturnType(ClassMethod $classMethod, Type $returnType): ?ClassMethod
    {
        if (! $returnType->isArray()->yes()) {
            return null;
        }

        // always returns array
        $classMethod->returnType = new Identifier('array');

        // add more precise array type if suitable
        if ($this->shouldAddReturnArrayDocType($returnType)) {
            $this->changeReturnType($classMethod, $returnType);
        }

        return $classMethod;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isPrivate()) {
            return true;
        }

        return $classMethod->returnType instanceof Node;
    }

    private function changeReturnType(ClassMethod $classMethod, Type $arrayType): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

        // skip already filled type, on purpose
        if (! $phpDocInfo->getReturnType() instanceof MixedType) {
            return;
        }

        // can handle only exactly 1-type array
        if ($arrayType instanceof ConstantArrayType && count($arrayType->getValueTypes()) !== 1) {
            return;
        }

        $itemType = $arrayType->getIterableValueType();
        if ($itemType instanceof IntersectionType) {
            $narrowArrayType = $arrayType;
        } else {
            $narrowArrayType = new ArrayType(new MixedType(), $itemType);
        }

        if ($arrayType->isList()->yes()) {
            $narrowArrayType = TypeCombinator::intersect($narrowArrayType, new AccessoryArrayListType());
        }

        $this->phpDocTypeChanger->changeReturnType($classMethod, $phpDocInfo, $narrowArrayType);
    }

    /**
     * @param Variable[] $variables
     * @return Variable[]
     */
    private function matchVariableNotOverriddenByNonArray(ClassMethod $classMethod, array $variables): array
    {
        // is variable overridden?
        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($classMethod, Assign::class);
        foreach ($assigns as $assign) {
            if (! $assign->var instanceof Variable) {
                continue;
            }

            foreach ($variables as $key => $variable) {
                if (! $this->nodeNameResolver->areNamesEqual($assign->var, $variable)) {
                    continue;
                }

                if ($assign->expr instanceof Array_) {
                    continue;
                }

                $nativeType = $this->nodeTypeResolver->getNativeType($assign->expr);
                if (! $nativeType->isArray()->yes()) {
                    unset($variables[$key]);
                }
            }
        }

        return $variables;
    }

    /**
     * @param Stmt[] $stmts
     * @return Variable[]
     */
    private function matchArrayAssignedVariable(array $stmts): array
    {
        $variables = [];
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
                $variables[] = $assign->var;
            }
        }

        return $variables;
    }

    private function shouldAddReturnArrayDocType(Type $arrayType): bool
    {
        if ($arrayType instanceof ConstantArrayType) {
            if ($arrayType->getIterableValueType() instanceof NeverType) {
                return false;
            }

            // handle only simple arrays
            if (! $arrayType->getIterableKeyType()->isInteger()->yes()) {
                return false;
            }
        }

        return true;
    }
}
