<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration;
use Rector\ValueObject\PhpVersionFeature;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\AddParamTypeDeclarationRectorTest
 */
final class AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration[]
     */
    private array $addParamTypeForFunctionLikeParamDeclarations = [];

    private bool $hasChanged = false;

    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly StaticTypeMapper $staticTypeMapper,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add param types where needed', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
(new SomeClass)->process(function ($parameter) {});
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
(new SomeClass)->process(function (string $parameter) {});
CODE_SAMPLE
                ,
                [
                    new AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration(
                        'SomeClass',
                        'process',
                        0,
                        0,
                        new StringType()
                    ),
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param CallLike $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->addParamTypeForFunctionLikeParamDeclarations as $addParamTypeForFunctionLikeParamDeclaration) {
            $type = match (true) {
                $node instanceof MethodCall => $node->var,
                $node instanceof StaticCall => $node->class,
                default => null,
            };

            if ($type === null) {
                continue;
            }

            if (! $this->isObjectType($type, $addParamTypeForFunctionLikeParamDeclaration->getObjectType())) {
                continue;
            }

            if (($node->name ?? null) === null) {
                continue;
            }

            if (! $node->name instanceof Identifier) {
                continue;
            }

            if (! $this->isName($node->name, $addParamTypeForFunctionLikeParamDeclaration->getMethodName())) {
                continue;
            }

            $this->processFunctionLike($node, $addParamTypeForFunctionLikeParamDeclaration);
        }

        if (! $this->hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration::class);

        $this->addParamTypeForFunctionLikeParamDeclarations = $configuration;
    }

    private function processFunctionLike(
        CallLike $callLike,
        AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration $addParamTypeForFunctionLikeWithinCallLikeParamDeclaration
    ): void {
        if (is_int($addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getCallLikePosition())) {
            if ($callLike->getArgs() === []) {
                return;
            }

            $arg = $callLike->args[$addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getCallLikePosition()] ?? null;

            if (! $arg instanceof Arg) {
                return;
            }

            // int positions shouldn't have names
            if ($arg->name !== null) {
                return;
            }
        } else {
            $args = array_filter($callLike->getArgs(), static function (Arg $arg) use (
                $addParamTypeForFunctionLikeWithinCallLikeParamDeclaration
            ): bool {
                if ($arg->name === null) {
                    return false;
                }

                return $arg->name->name === $addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getCallLikePosition();
            });

            if ($args === []) {
                return;
            }

            $arg = array_values($args)[0];
        }

        $functionLike = $arg->value;
        if (! $functionLike instanceof FunctionLike) {
            return;
        }

        if (! isset($functionLike->params[$addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getFunctionLikePosition()])) {
            return;
        }

        $this->refactorParameter(
            $functionLike->params[$addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getFunctionLikePosition()],
            $addParamTypeForFunctionLikeWithinCallLikeParamDeclaration
        );
    }

    private function refactorParameter(
        Param $param,
        AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration $addParamTypeForFunctionLikeWithinCallLikeParamDeclaration
    ): void {
        // already set → no change
        if ($param->type !== null) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual(
                $currentParamType,
                $addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getParamType()
            )) {
                return;
            }
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            $addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getParamType(),
            TypeKind::PARAM
        );

        $this->hasChanged = true;

        // remove it
        if ($addParamTypeForFunctionLikeWithinCallLikeParamDeclaration->getParamType() instanceof MixedType) {
            if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::MIXED_TYPE)) {
                $param->type = $paramTypeNode;
                return;
            }

            $param->type = null;
            return;
        }

        $param->type = $paramTypeNode;
    }
}
