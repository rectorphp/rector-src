<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallRector\RemoveFuncCallRectorTest
 */
final class RemoveFuncCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string[]
     */
    private array $removedFunctions = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove function', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$x = 'something';
var_dump($x);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$x = 'something';
CODE_SAMPLE
                ,
                ['var_dump']
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class, Expression::class];
    }

    /**
     * @param FuncCall|Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        $expr = $node->expr;

        if (! $expr instanceof FuncCall) {
            return null;
        }

        $removed = $this->removeNodeIfNeeded($expr);

        if (! $removed) {
            $this->removeNodeIfNeeded($node);
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString($configuration);
        $this->removedFunctions = $configuration;
    }

    private function removeNodeIfNeeded(FuncCall $node): bool
    {
        $removed = false;

        foreach ($this->removedFunctions as $removedFunction) {
            if (!$this->isName($node->name, $removedFunction)) {
                continue;
            }

            $this->removeNode($node);

            $removed = true;

            break;
        }

        return $removed;
    }
}
