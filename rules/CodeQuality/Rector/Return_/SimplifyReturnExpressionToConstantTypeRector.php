<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Return_;

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
     * @param \PhpParser\Node\Stmt\Return_ $node
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

            if (!$stmt instanceof Node\Stmt\Return_
                || $this->shouldSkip($stmt)
                || $stmt->expr === null
            ) {
                continue;
            }

            $nativeType = $this->nodeTypeResolver->getNativeType($stmt->expr);
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
                    $stmt->expr = new Node\Expr\ConstFetch(new Node\Name($constName));
                    $hasChanged = true;
                }
            }

            if ($nativeType instanceof EnumCaseObjectType) {
                $stmt->expr = new Node\Expr\ConstFetch(new Node\Name($nativeType->describe(VerbosityLevel::precise())));
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function shouldSkip(Node\Stmt\Return_ $node): bool
    {
        if ($node->expr === null) {
            return true;
        }

        if (!$node->expr instanceof Node\Expr\Variable) {
            return true;
        }

        if ($this->variableAnalyzer->isStaticOrGlobal($node->expr)) {
            return true;
        }

        if ($this->variableAnalyzer->isUsedByReference($node->expr)) {
            return true;
        }

        return false;
    }
}
