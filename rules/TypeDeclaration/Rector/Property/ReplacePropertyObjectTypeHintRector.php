<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\ReplaceObjectTypeHint;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Property\ReplacePropertyObjectTypeHintRector\ReplacePropertyObjectTypeHintRectorTest
 */
final class ReplacePropertyObjectTypeHintRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var ReplaceObjectTypeHint[]
     */
    private array $replaceObjectTypeHints;

    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace property object type hints with new object type hint',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    property \Carbon\Carbon $carbon;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    property \Carbon\CarbonInterface $carbon;
}
CODE_SAMPLE
                    ,
                    [
                        new ReplaceObjectTypeHint(new ObjectType('\Carbon\Carbon'), new ObjectType(
                            '\Carbon\CarbonInterface'
                        )),
                    ]
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Property
    {
        foreach ($this->replaceObjectTypeHints as $replaceObjectTypeHint) {
            if (! $node->type instanceof Identifier && ! $node->type instanceof Name) {
                continue;
            }

            if ($this->isObjectType($node->type, $replaceObjectTypeHint->getOriginalObjectType())) {
                $node->type = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                    $replaceObjectTypeHint->getReplacingObjectType(),
                    TypeKind::PROPERTY
                );

                return $node;
            }
        }

        return null;
    }

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ReplaceObjectTypeHint::class);
        $this->replaceObjectTypeHints = $configuration;
    }
}
