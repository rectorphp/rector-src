<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Expr\Cast\Double;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://stackoverflow.com/questions/5577003/using-settype-in-php-instead-of-typecasting-using-brackets-what-is-the-differen/5577068#5577068
 *
 * @see \Rector\Tests\CodeQuality\Rector\FuncCall\SetTypeToCastRector\SetTypeToCastRectorTest
 */
final class SetTypeToCastRector extends AbstractRector
{
    /**
     * @var array<string, class-string<Cast>>
     */
    private const TYPE_TO_CAST = [
        'array' => Array_::class,
        'bool' => Bool_::class,
        'boolean' => Bool_::class,
        'double' => Double::class,
        'float' => Double::class,
        'int' => Int_::class,
        'integer' => Int_::class,
        'object' => Object_::class,
        'string' => String_::class,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes settype() to (type) where possible', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($foo)
    {
        settype($foo, 'string');

        return settype($foo, 'integer');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($foo)
    {
        $foo = (string) $foo;

        return (int) $foo;
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
        return [FuncCall::class, Expression::class, Assign::class, ArrayItem::class, Arg::class];
    }

    /**
     * @param FuncCall|Expression|Assign|Expr\ArrayItem|Node\Arg $node
     */
    public function refactor(Node $node)
    {
        if ($node instanceof Arg || $node instanceof ArrayItem) {
            if ($this->isSetTypeFuncCall($node->value)) {
                return NodeTraverser::STOP_TRAVERSAL;
            }

            return null;
        }

        if ($node instanceof Assign) {
            if (! $this->isSetTypeFuncCall($node->expr)) {
                return null;
            }

            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }

        if ($node instanceof Expression) {
            if (! $node->expr instanceof FuncCall) {
                return null;
            }

            return $this->refactorFuncCall($node->expr, true);
        }

        return $this->refactorFuncCall($node, false);
    }

    private function refactorFuncCall(FuncCall $funcCall, bool $isStandaloneExpression): Assign|null|Cast
    {
        if (! $this->isSetTypeFuncCall($funcCall)) {
            return null;
        }

        if ($funcCall->isFirstClassCallable()) {
            return null;
        }

        $typeValue = $this->valueResolver->getValue($funcCall->getArgs()[1]->value);
        if (! is_string($typeValue)) {
            return null;
        }

        $typeValue = strtolower($typeValue);

        $variable = $funcCall->getArgs()[0]
            ->value;

        if (isset(self::TYPE_TO_CAST[$typeValue])) {
            $castClass = self::TYPE_TO_CAST[$typeValue];
            $castNode = new $castClass($variable);

            if (! $isStandaloneExpression) {
                return $castNode;
            }

            // bare expression? → assign
            return new Assign($variable, $castNode);
        }

        if ($typeValue === 'null') {
            return new Assign($variable, $this->nodeFactory->createNull());
        }

        return null;
    }

    private function isSetTypeFuncCall(Expr $expr): bool
    {
        // skip assign of settype() calls
        if (! $expr instanceof FuncCall) {
            return false;
        }

        return $this->isName($expr, 'settype');
    }
}
