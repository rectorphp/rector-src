<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\ClassConstFetch;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\ClassConstFetchToValue;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\ClassConstFetch\ClassConstFetchToValueRector\ClassConstFetchToValueRectorTest
 */
final class ClassConstFetchToValueRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const CLASS_CONST_FETCHES_TO_VALUES = 'old_constants_to_new_valuesByType';

    /**
     * @var ClassConstFetchToValue[]
     */
    private array $classConstFetchesToValues = [];

    public function getRuleDefinition(): RuleDefinition
    {
        $configuration = [
            self::CLASS_CONST_FETCHES_TO_VALUES => [
                new ClassConstFetchToValue('Nette\Configurator', 'DEVELOPMENT', 'development'),
                new ClassConstFetchToValue('Nette\Configurator', 'PRODUCTION', 'production'),
            ],
        ];

        return new RuleDefinition('Replaces constant by value', [
            new ConfiguredCodeSample(
                '$value === Nette\Configurator::DEVELOPMENT',
                '$value === "development"',
                $configuration
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    /**
     * @param ClassConstFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->classConstFetchesToValues as $classConstFetchToValue) {
            if (! $this->isObjectType($node->class, $classConstFetchToValue->getObjectType())) {
                continue;
            }

            if (! $this->isName($node->name, $classConstFetchToValue->getConstant())) {
                continue;
            }

            return BuilderHelpers::normalizeValue($classConstFetchToValue->getValue());
        }

        return $node;
    }

    /**
     * @param array<string, ClassConstFetchToValue[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $classConstFetchesToValues = $configuration[self::CLASS_CONST_FETCHES_TO_VALUES] ?? ($configuration ?: []);
        Assert::allIsInstanceOf($classConstFetchesToValues, ClassConstFetchToValue::class);

        $this->classConstFetchesToValues = $classConstFetchesToValues;
    }
}
