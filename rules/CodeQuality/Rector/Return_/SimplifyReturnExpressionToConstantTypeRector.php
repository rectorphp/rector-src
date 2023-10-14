<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Return_;

use PhpParser\Node;
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

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Stmt\Return_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->expr === null) {
            return null;
        }

        $nativeType = $this->nodeTypeResolver->getNativeType($node->expr);
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
                $node->expr = new Node\Expr\ConstFetch(new Node\Name($constName));
                return $node;
            }
        }

        if ($nativeType instanceof EnumCaseObjectType) {
            $node->expr = new Node\Expr\ConstFetch(new Node\Name($nativeType->describe(VerbosityLevel::precise())));
            return $node;
        }

        return null;
    }
}
