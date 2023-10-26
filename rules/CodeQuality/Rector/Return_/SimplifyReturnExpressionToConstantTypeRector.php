<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Return_;

use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeAnalyzer\VariableAnalyzer;
use Rector\Core\Rector\AbstractRector;
use PHPStan\Type\ConstantScalarType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PHPStan\Type\Enum\EnumCaseObjectType;
use PHPStan\Type\VerbosityLevel;


/**

 * @see \Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\SimplifyReturnExpressionToConstantTypeRectorTest
 */
final class SimplifyReturnExpressionToConstantTypeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Simplify return expression to constant values', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $return = false;

        if (doSomething()) {
            return true;
        }

        return $return;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $return = false;

        if (doSomething()) {
            return true;
        }

        return false;
    }
}
CODE_SAMPLE

            )
        ]);
    }

    public function __construct(
        private readonly VariableAnalyzer $variableAnalyzer
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Expression
                && $stmt->expr instanceof FuncCall
                && $this->isName( $stmt->expr,'extract'))
            {
                $hasChanged = false;
                break;
            }
            if (!$stmt instanceof Return_) {
                continue;
            }
            if ($this->shouldSkip($stmt)) {
                continue;
            }
            if ($stmt->expr === null) {
                continue;
            }

            $constantFetch = $this->getConstantFetchValueExpression($stmt->expr);
            if ($constantFetch !== null) {
                $stmt->expr = $constantFetch;
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function shouldSkip(Return_ $return): bool
    {
        if ($return->expr === null) {
            return true;
        }

        if (!$return->expr instanceof Variable) {
            return true;
        }

        if ($this->variableAnalyzer->isStaticOrGlobal($return->expr)) {
            return true;
        }
        return $this->variableAnalyzer->isUsedByReference($return->expr);
    }

    private function getConstantFetchValueExpression(Node\Expr $expr): ?ConstFetch
    {
        $nativeType = $this->nodeTypeResolver->getNativeType($expr);
        if ($nativeType instanceof ConstantScalarType) {
            $constantValue = $nativeType->getValue();
            $constName = null;
            if (is_bool($constantValue)) {
                $constName = $constantValue ? 'true' : 'false';
            }

            if (is_null($constantValue)) {
                $constName = 'null';
            }

            if ($constName !== null) {
                return new ConstFetch(new Name($constName));
            }
        }

        if ($nativeType instanceof EnumCaseObjectType) {
            return new ConstFetch(new Name($nativeType->describe(VerbosityLevel::precise())));
        }

        return null;
    }
}
