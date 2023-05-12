<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\CodingStyle\ValueObject\OrderArray\OrderArrayParam;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Source\Groups;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\OrderArrayParamRectorTest
 * @see \Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\OrderArrayParamRectorTest
 */
final class OrderArrayParamRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    /**
     * @var OrderArrayParam[]
     */
    private array $configuration = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Order attributes by asc or desc', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
#[Groups(['b', 'a'])]
class MyClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[Groups(['a', 'b'])]
class MyClass
{
}
CODE_SAMPLE
                ,
                [
                    Groups::class => OrderArrayParamRector::ASC,
                ],
            ),
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
#[Groups(['a', 'b'])]
class MyClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[Groups(['b', 'a'])]
class MyClass
{
}
CODE_SAMPLE
                ,
                [
                    Groups::class => OrderArrayParamRector::DESC,
                ],
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
            Property::class,
        ];
    }

    /**
     * @param Class_|Property $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $this->sortClassName($attr);
            }
        }
        return $node;
    }

    private function sortClassName(Attribute $attr): void
    {
        $className = $this->getName($attr->name);
        foreach ($this->configuration as $configuration) {
            foreach ($configuration->getConfig() as $className2 => $sortOrder) {
                if ($className2 === $className) {
                    $this->sort($attr, $sortOrder);
                }
            }
        }
    }

    private function sort(Attribute $attr, string $sortOrder): void
    {
        foreach ($attr->args as $index => $arg) {
            /** @var Array_ $value */
            $value = $arg->value;
            if (isset($value->items) && is_array($value->items)) {
                usort($value->items, static function (
                    ArrayItem $first,
                    ArrayItem $second
                ) use ($sortOrder): int {
                    /** @var String_ $firstValue */
                    $firstValue = $first->value;
                    /** @var String_ $secondValue */
                    $secondValue = $second->value;
                    if ($sortOrder === self::ASC) {
                        return strcmp($firstValue->value, $secondValue->value);
                    }
                    return strcmp($secondValue->value, $firstValue->value);
                });
            }
            $attr->args[$index] = $arg;
        }
    }

    public function configure(array $configuration): void
    {
        Assert::minCount($configuration, 1);
        $this->configuration = $configuration;
    }
}
