<?php

declare(strict_types=1);

namespace Rector\Php82\Rector\Param;

use PhpParser\Node\Param;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Attribute;
use PhpParser\Node\Name\FullyQualified;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use PhpParser\Node;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Php82\Rector\Param\AddSensitiveParameterAttributeRector\AddSensitiveParameterAttributeRectorTest
 */
final class AddSensitiveParameterAttributeRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface
{
    public const SENSITIVE_PARAMETERS = 'sensitive_parameters';

    /**
     * @var string[]
     */
    private array $sensitiveParameters = [];

    public function __construct(protected PhpAttributeAnalyzer $phpAttributeAnalyzer)
    {
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString($configuration[self::SENSITIVE_PARAMETERS] ?? []);
        $this->sensitiveParameters = (array) ($configuration[self::SENSITIVE_PARAMETERS] ?? []);
    }

    public function getNodeTypes(): array
    {
        return [Param::class];
    }

    /**
     * @param Node\Param $node
     */
    public function refactor(Node $node): ?Param
    {
        if (! $this->isNames($node, $this->sensitiveParameters)) {
            return null;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($node, 'SensitiveParameter')) {
            return null;
        }

        $node->attrGroups[] = new AttributeGroup([
            new Attribute(new FullyQualified('SensitiveParameter')),
        ]);

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add SensitiveParameter attribute to method and function configured parameters',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(string $password)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(#[\SensitiveParameter] string $password)
    {
    }
}
CODE_SAMPLE
                    ,
                    [
                        self::SENSITIVE_PARAMETERS => ['password'],
                    ]
                ),

            ]
        );
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SENSITIVE_PARAMETER_ATTRIBUTE;
    }
}
