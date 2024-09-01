<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Param;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
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
 * @see \Rector\Tests\TypeDeclaration\Rector\Param\ReplaceParamObjectTypeHintRector\ReplaceParamObjectTypeHintRectorTest
 */
class ReplaceParamObjectTypeHintRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var ReplaceObjectTypeHint[]
     */
    private array $replaceObjectTypeHints;

    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ReplaceObjectTypeHint::class);
        $this->replaceObjectTypeHints = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace object type hints on Parameters with object type hints',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
function (\Carbon\Carbon $carbon) {}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
function (\Carbon\CarbonInterface $carbon) {}
CODE_SAMPLE
                    ,
                    [
                        new ReplaceObjectTypeHint(
                            new ObjectType('Carbon\Carbon'),
                            new ObjectType('\Carbon\CarbonInterface')
                        ),
                    ],
                )]
        );
    }

    public function getNodeTypes(): array
    {
        return [Param::class];
    }

    /**
     * @param Param $node
     */
    public function refactor(Node $node): ?Param
    {
        $changed = false;
        foreach ($this->replaceObjectTypeHints as $replaceObjectTypeHint) {
            if (! $node->type instanceof Identifier && ! $node->type instanceof Name) {
                continue;
            }

            if ($this->isObjectType($node->type, $replaceObjectTypeHint->getOriginalObjectType())) {

                $node->type = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                    $replaceObjectTypeHint->getReplacingObjectType(),
                    TypeKind::PROPERTY
                );

                $changed = true;
            }
        }

        if ($changed) {
            return $node;
        }

        return null;
    }
}
