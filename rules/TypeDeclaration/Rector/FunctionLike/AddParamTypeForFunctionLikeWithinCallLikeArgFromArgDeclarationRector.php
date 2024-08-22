<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration;
use Rector\ValueObject\PhpVersionFeature;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRectorTest
 */
final class AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration[]
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
$app->extend(SomeClass::class, function ($parameter) {});
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$app->extend(SomeClass::class, function (SomeClass $parameter) {});
CODE_SAMPLE
                ,
                [
                    new AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration(
                        'SomeClass',
                        'extend',
                        1,
                        0,
                        0,
                        true,
                    ),
                ]
            ),
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$app->tag($stringVar, function ($parameter) {});
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$app->tag($stringVar, function (string $parameter) {});
CODE_SAMPLE
                ,
                [
                    new AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration(
                        'SomeClass',
                        'tag',
                        1,
                        0,
                        0,
                        false,
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
        $this->hasChanged = false;
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
        Assert::allIsAOf($configuration, AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration::class);

        $this->addParamTypeForFunctionLikeParamDeclarations = $configuration;
    }

    private function processFunctionLike(
        CallLike $callLike,
        AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration $addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration
    ): void {
        if ($callLike->isFirstClassCallable()) {
            return;
        }

        if (is_int($addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->getCallLikePosition())) {
            if ($callLike->getArgs() === []) {
                return;
            }

            $arg = $callLike->args[$addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->getCallLikePosition()] ?? null;

            if (! $arg instanceof Arg) {
                return;
            }

            // int positions shouldn't have names
            if ($arg->name !== null) {
                return;
            }
        } else {
            $args = array_filter($callLike->getArgs(), static function (Arg $arg) use (
                $addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration
            ): bool {
                if ($arg->name === null) {
                    return false;
                }

                return $arg->name->name === $addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->getCallLikePosition();
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

        if (! isset($functionLike->params[$addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->getFunctionLikePosition()])) {
            return;
        }

        if (! ($arg = $this->getArg(
            $addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->getFromArgPosition(),
            $callLike->getArgs()
        )) instanceof Arg) {
            return;
        }

        $this->refactorParameter(
            $functionLike->params[$addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->getFunctionLikePosition()],
            $arg,
            $addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->onlyAcceptClassString()
        );
    }

    /**
     * @param Arg[] $args
     */
    private function getArg(int|string $position, array $args): ?Arg
    {
        return $args[$position] ?? null;
    }

    private function refactorParameter(Param $param, Arg $arg, bool $classStringOnly): void
    {
        $paramOrigin = $arg->value;

        if ($classStringOnly &&
        $paramOrigin instanceof ClassConstFetch &&
        $paramOrigin->name instanceof Identifier &&
        $paramOrigin->class instanceof Name &&
        $paramOrigin->name->name === 'class') {
            $newParameterType = new ObjectType($paramOrigin->class->toString());
        } elseif (! $classStringOnly) {
            $newParameterType = $this->getType($paramOrigin);
        } else {
            return;
        }

        // already set → no change
        if ($param->type !== null) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual($currentParamType, $newParameterType)) {
                return;
            }
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            $newParameterType,
            TypeKind::PARAM
        );

        $this->hasChanged = true;

        // remove it
        if ($newParameterType instanceof MixedType) {
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
