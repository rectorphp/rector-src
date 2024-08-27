<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\AddClosureParamTypeFromArg;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\AddClosureParamTypeFromArgRectorTest
 */
final class AddClosureParamTypeFromArgClassStringRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var AddClosureParamTypeFromArg[]
     */
    private array $addParamTypeForFunctionLikeParamDeclarations = [];

    private bool $hasChanged = false;

    public function __construct(
        private readonly TypeComparator $typeComparator,
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
                [new AddClosureParamTypeFromArg('SomeClass', 'extend', 1, 0, 0)]
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
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->hasChanged = false;

        foreach ($this->addParamTypeForFunctionLikeParamDeclarations as $addParamTypeForFunctionLikeParamDeclaration) {
            if ($node instanceof MethodCall) {
                $caller = $node->var;
            } elseif ($node instanceof StaticCall) {
                $caller = $node->class;
            } else {
                continue;
            }

            if (! $this->isObjectType($caller, $addParamTypeForFunctionLikeParamDeclaration->getObjectType())) {
                continue;
            }

            if (! $node->name instanceof Identifier) {
                continue;
            }

            if (! $this->isName($node->name, $addParamTypeForFunctionLikeParamDeclaration->getMethodName())) {
                continue;
            }

            $this->processCallLike($node, $addParamTypeForFunctionLikeParamDeclaration);
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
        Assert::allIsAOf($configuration, AddClosureParamTypeFromArg::class);

        $this->addParamTypeForFunctionLikeParamDeclarations = $configuration;
    }

    private function processCallLike(
        MethodCall|StaticCall $callLike,
        AddClosureParamTypeFromArg $addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration
    ): void {
        if ($callLike->isFirstClassCallable()) {
            return;
        }

        if ($callLike->getArgs() === []) {
            return;
        }

        $arg = $callLike->args[$addParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration->getCallLikePosition()] ?? null;
        if (! $arg instanceof Arg) {
            return;
        }

        // int positions shouldn't have names
        if ($arg->name instanceof Identifier) {
            return;
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
        );
    }

    /**
     * @param Arg[] $args
     */
    private function getArg(int $position, array $args): ?Arg
    {
        return $args[$position] ?? null;
    }

    private function refactorParameter(Param $param, Arg $arg): void
    {
        $paramOrigin = $arg->value;

        $argType = $this->nodeTypeResolver->getType($arg->value);
        if (! $argType instanceof ConstantStringType) {
            return;
        }

        $objectType = new ObjectType($argType->getValue());

        // already set → no change
        if ($param->type !== null) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual($currentParamType, $objectType)) {
                return;
            }
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($objectType, TypeKind::PARAM);
        $this->hasChanged = true;

        $param->type = $paramTypeNode;
    }
}
