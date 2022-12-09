<?php

declare(strict_types=1);

namespace Rector;


use EasyCI202211\PhpParser\Node\Expr\MethodCall;
use PhpParser\Node;
use PhpParser\Node\Expr\ErrorSuppress;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Webmozart\Assert\Assert;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RectorReplaceErrorSuppression extends AbstractRector implements ConfigurableRectorInterface
{

    private array $replacementClass;

    /**
     * What nodes are we looking for
     */
    public function getNodeTypes() : array
    {
        return [ErrorSuppress::class];
    }

    public function configure(array $configuration) : void
    {
        Assert::string($configuration['className']);
        Assert::string($configuration['methodName']);

        $this->replacementClass = $configuration;
    }

    public function refactor(Node $node)
    {

        if (!$node instanceof ErrorSuppress) {
            return null;
        }

        if ($node->expr instanceof Node\Expr\MethodCall) {
            return $this->processMethodCall($node->expr);
        } elseif ($node->expr instanceof Node\Expr\FuncCall) {
            return $this->processFunctionCall($node->expr);
        } elseif ($node->expr instanceof Node\Expr\StaticCall) {
            return $this->processStaticMethodCall($node->expr);
        }

        return null;
    }

    private function processFunctionCall(Node\Expr\FuncCall $node)
    {
        $newArgs = [];
        foreach ($node->getArgs() as $arg) {
            $newArgs[] = $arg->value;
        }

        if (empty($newArgs)) {
            $newNode = $this->nodeFactory->createStaticCall(
                $this->replacementClass['className'],
                $this->replacementClass['methodName'],
                [$node->name->toString()]
            );
        } else {
            $newNode = $this->nodeFactory->createStaticCall(
                $this->replacementClass['className'],
                $this->replacementClass['methodName'],
                [
                    $node->name->toString(),
                    $newArgs,
                ]
            );
        }

        return $newNode;
    }

    private function processMethodCall(Node\Expr\MethodCall $node)
    {
        $newArgs = [];
        foreach ($node->getArgs() as $arg) {
            $newArgs[] = $arg->value;
        }

        if (empty($newArgs)) {
            $newNode = $this->nodeFactory->createStaticCall(
                $this->replacementClass['className'],
                $this->replacementClass['methodName'],
                [
                    [$node->var, $node->name->toString()],
                ]
            );
        } else {
            $newNode = $this->nodeFactory->createStaticCall(
                $this->replacementClass['className'],
                $this->replacementClass['methodName'],
                [
                    [$node->var, $node->name->toString()],
                    $newArgs,
                ]
            );
        }

        return $newNode;
    }

    private function processStaticMethodCall(Node\Expr\StaticCall $node)
    {
        $newArgs = [];
        foreach ($node->getArgs() as $arg) {
            $newArgs[] = $arg->value;
        }

        if (empty($newArgs)) {
            $newNode = $this->nodeFactory->createStaticCall(
                $this->replacementClass['className'],
                $this->replacementClass['methodName'],
                [
                    [$node->class->toString(), $node->name->toString()],
                ]
            );
        } else {
            $newNode = $this->nodeFactory->createStaticCall(
                $this->replacementClass['className'],
                $this->replacementClass['methodName'],
                [
                    [$node->class->toString(), $node->name->toString()],
                    $newArgs,
                ]
            );
        }

        return $newNode;
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition(
            'Replace error suppression with static call',
            [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
$x = @foo_function_call($arg1, $arg2)
CODE_SAMPLE
                ,<<<'CODE_SAMPLE'
$c = className::classMethod('foo_function_call',[$arg1, $arg2]);
CODE_SAMPLE
                , ['className' => '\Tests\App', 'methodName' => 'getDefine'])]);
    }
}
