<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\While_;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://bugs.php.net/bug.php?id=70112
 *
 * @see \Rector\Tests\DowngradePhp70\Rector\FuncCall\DowngradeDirnameLevelsRector\DowngradeDirnameLevelsRectorTest
 */
final class DowngradeDirnameLevelsRector extends AbstractRector
{
    /**
     * @var string
     */
    private const DIRNAME = 'dirname';

    public function __construct(
        private VariableNaming $variableNaming
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace the 2nd argument of dirname() by a closure with a loop',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return dirname($path, $levels);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$dirnameFunc = function ($path, $levels) {
    $dir = null;
    while (--$levels >= 0) {
        $dir = dirname($dir ?: $path);
    }
    return $dir;
};
return $dirnameFunc($path, $levels);
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $levelsArg = $this->getLevelsArg($node);
        if ($levelsArg === null) {
            return null;
        }

        $currentStmt = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $scope = $currentStmt->getAttribute(AttributeKey::SCOPE);

        $funcVariable = new Variable($this->variableNaming->createCountedValueName('dirnameFunc', $scope));

        $closure = $this->createClosure();
        $exprAssignClosure = $this->createExprAssign($funcVariable, $closure);

        $this->nodesToAddCollector->addNodesBeforeNode([$exprAssignClosure], $node);

        $node->name = $funcVariable;

        return $node;
    }

    private function getLevelsArg(FuncCall $funcCall): ?Arg
    {
        if (! $this->isName($funcCall, self::DIRNAME)) {
            return null;
        }

        if (! isset($funcCall->args[1])) {
            return null;
        }

        if (! $funcCall->args[1] instanceof Arg) {
            return null;
        }

        return $funcCall->args[1];
    }

    private function createExprAssign(Variable $var, Expr $expr): Expression
    {
        return new Expression(new Assign($var, $expr));
    }

    private function createClosure(): Closure
    {
        $dirVariable = new Variable('dir');
        $pathVariable = new Variable('path');
        $levelsVariable = new Variable('levels');

        $closure = new Closure();
        $closure->params = [
            new Param($pathVariable),
            new Param($levelsVariable),
        ];
        $closure->stmts[] = $this->createExprAssign($dirVariable, $this->nodeFactory->createNull());

        $whileCond = new GreaterOrEqual(new PreDec($levelsVariable), new LNumber(0));
        $closure->stmts[] = new While_(
            $whileCond,
            [$this->createExprAssign(
                $dirVariable,
                $this->createDirnameFuncCall(new Arg(new Ternary($dirVariable, null, $pathVariable)))
            )]
        );
        $closure->stmts[] = new Return_($dirVariable);

        return $closure;
    }

    private function createDirnameFuncCall(Arg $pathArg): FuncCall
    {
        return new FuncCall(new Name(self::DIRNAME), [$pathArg]);
    }
}
