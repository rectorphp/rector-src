<?php

declare(strict_types=1);

namespace Rector;


use PhpParser\Node;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Webmozart\Assert\Assert;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;

final class RectorReplaceDefinesWithMethodCalls extends AbstractRector implements ConfigurableRectorInterface
{

    private array $replacementClass;

    /**
     * What nodes are we looking for
     */
    public function getNodeTypes() : array
    {
        return [ConstFetch::class, FuncCall::class];
    }

    public function configure(array $configuration) : void
    {
        Assert::string($configuration['className']);
        Assert::string($configuration['methodName']);

        $this->replacementClass = $configuration;
    }

    public function refactor(Node $node): ?Node
    {
        $args = null;
        // its direct use
        if ($node instanceof FuncCall && $this->isName($node, 'constant')) {
            $args = $node->args;
        } elseif ($node instanceof ConstFetch) {
            $args = [$this->getName($node->name)];
        }

        if (null === $args) {
            return $node;
        }
        return $this->returnNewNode($args);
    }

    private function returnNewNode($args)
    {
        return $this->nodeFactory->createStaticCall(
            $this->replacementClass['className'],
            $this->replacementClass['methodName'],
            $args
        );
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition(
            'Replace constant by new ones',
            [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
SOME_DEFINE_NAME
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
className::classMethod('SOME_DEFINE_NAME');
CODE_SAMPLE
                , ['className' => '\Tests\App', 'methodName' => 'getDefine'])]);
    }
}
