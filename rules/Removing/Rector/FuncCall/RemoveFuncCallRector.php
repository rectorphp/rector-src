<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Removing\ValueObject\RemoveFuncCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallRector\RemoveFuncCallRectorTest
 */
final class RemoveFuncCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var RemoveFuncCall[]
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
                [new RemoveFuncCall('var_dump')]
            ),
        ]);
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
        foreach ($this->removedFunctions as $removedFunction) {
            if (! $this->isName($node->name, $removedFunction->getFunction())) {
                continue;
            }

            $this->removeNode($node);

            return null;
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RemoveFuncCall::class);
        $this->removedFunctions = $configuration;
    }
}
