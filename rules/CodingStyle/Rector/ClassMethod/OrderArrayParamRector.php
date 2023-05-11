<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Property;
use Rector\CodingStyle\ValueObject\OrderArrayParam;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\OrderArrayParamRectorTest
 */
final class OrderArrayParamRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var OrderArrayParam[]
     */
    private array $configuration = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Order attributes by desired names', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
#[Second]
#[First]
class Someclass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[First]
#[Second]
class Someclass
{
}
CODE_SAMPLE
                ,
                ['First', 'Second'],
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            Class_::class,
            //Property::class,
            //Param::class,
            //ClassMethod::class,
            //Function_::class,
            //Closure::class,
            //ArrowFunction::class,
        ];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $name = $this->getName($attr->name);
                $this->configuration;
                // todo if $this->configuration contains
                $t = 0;
            }
        }
        return $node;
    }

    /**
     * @param OrderArrayParam[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::minCount($configuration, 1);
        $this->configuration = $configuration;
    }
}
