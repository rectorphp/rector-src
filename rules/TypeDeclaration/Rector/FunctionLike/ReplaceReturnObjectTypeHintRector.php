<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
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
class ReplaceReturnObjectTypeHintRector extends AbstractRector implements ConfigurableRectorInterface
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
function foo(): \Carbon\Carbon {}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
function foo(): \Carbon\CarbonInterface {}
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
        return [Function_::class, ClassMethod::class, Closure::class];
    }

    /**
     * @param Function_|ClassMethod|Closure $node
     */
    public function refactor(Node $node): Function_|ClassMethod|Closure|null
    {
        foreach ($this->replaceObjectTypeHints as $replaceObjectTypeHint) {
            if (! $node->returnType instanceof Identifier && ! $node->returnType instanceof Name) {
                continue;
            }

            if ($this->isObjectType($node->returnType, $replaceObjectTypeHint->getOriginalObjectType())) {

                $node->returnType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                    $replaceObjectTypeHint->getReplacingObjectType(),
                    TypeKind::PROPERTY
                );

                return $node;
            }
        }

        return null;
    }
}
