<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use Rector\CodingStyle\ValueObject\OrderArray\OrderArrayParam;
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
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    private const KEY = 'next';

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
        ];
    }

    /**
     * @param Class_ $node
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
                    break;
                }
            }
        }
    }

    private function sort(Attribute $attr, string $sortOrder): void
    {
        /** @var Arg $arg */
        $arg = $attr->name->getAttribute(self::KEY);
        /** @var Array_ $value */
        $value = $arg->value;
        usort($value->items, static function (
            ArrayItem $first,
            ArrayItem $second
        ) use ($sortOrder) {
            /** @var String_ $firstValue */
            $firstValue = $first->value;
            /** @var String_ $secondValue */
            $secondValue = $second->value;
            if ($sortOrder === self::ASC) {
                return strcmp($firstValue->value, $secondValue->value);
            }
            return strcmp($secondValue->value, $firstValue->value);
        });
        $attr->name->setAttribute(self::KEY, $value);
    }

    public function configure(array $configuration): void
    {
        Assert::minCount($configuration, 1);
        $this->configuration = $configuration;
    }
}
