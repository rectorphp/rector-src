<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
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
final class AddClosureParamTypeFromArgRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var AddClosureParamTypeFromArg[]
     */
    private array $addClosureParamTypeFromArgs = [];

    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add closure param type based on known passed service/string types of method calls', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$app = new Container();
$app->extend(SomeClass::class, function ($parameter) {});
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$app = new Container();
$app->extend(SomeClass::class, function (SomeClass $parameter) {});
CODE_SAMPLE
                ,
                [new AddClosureParamTypeFromArg('Container', 'extend', 1, 0, 0)]
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
        foreach ($this->addClosureParamTypeFromArgs as $addClosureParamTypeFromArg) {
            if ($node instanceof MethodCall) {
                $caller = $node->var;
            } elseif ($node instanceof StaticCall) {
                $caller = $node->class;
            } else {
                continue;
            }

            if (! $this->isCallMatch($caller, $addClosureParamTypeFromArg, $node)) {
                continue;
            }

            return $this->processCallLike($node, $addClosureParamTypeFromArg);
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AddClosureParamTypeFromArg::class);

        $this->addClosureParamTypeFromArgs = $configuration;
    }

    private function processCallLike(
        MethodCall|StaticCall $callLike,
        AddClosureParamTypeFromArg $addClosureParamTypeFromArg
    ): MethodCall|StaticCall|null {
        if ($callLike->isFirstClassCallable()) {
            return null;
        }

        $callLikeArg = $callLike->args[$addClosureParamTypeFromArg->getCallLikePosition()] ?? null;
        if (! $callLikeArg instanceof Arg) {
            return null;
        }

        // int positions shouldn't have names
        if ($callLikeArg->name instanceof Identifier) {
            return null;
        }

        $functionLike = $callLikeArg->value;
        if (! $functionLike instanceof Closure && ! $functionLike instanceof ArrowFunction) {
            return null;
        }

        if (! isset($functionLike->params[$addClosureParamTypeFromArg->getFunctionLikePosition()])) {
            return null;
        }

        $callLikeArg = $callLike->getArgs()[$addClosureParamTypeFromArg->getFromArgPosition()] ?? null;
        if (! $callLikeArg instanceof Arg) {
            return null;
        }

        $hasChanged = $this->refactorParameter(
            $functionLike->params[$addClosureParamTypeFromArg->getFunctionLikePosition()],
            $callLikeArg,
        );

        if ($hasChanged) {
            return $callLike;
        }

        return null;
    }

    private function refactorParameter(Param $param, Arg $arg): bool
    {
        $closureType = $this->resolveClosureType($arg->value);
        if (! $closureType instanceof Type) {
            return false;
        }

        // already set → no change
        if ($param->type instanceof Node) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual($currentParamType, $closureType)) {
                return false;
            }
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($closureType, TypeKind::PARAM);
        $param->type = $paramTypeNode;

        return true;
    }

    private function isCallMatch(
        Name|Expr $caller,
        AddClosureParamTypeFromArg $addClosureParamTypeFromArg,
        StaticCall|MethodCall $call
    ): bool {
        if (! $this->isObjectType($caller, $addClosureParamTypeFromArg->getObjectType())) {
            return false;
        }

        return $this->isName($call->name, $addClosureParamTypeFromArg->getMethodName());
    }

    private function resolveClosureType(Expr $expr): ?Type
    {
        $exprType = $this->nodeTypeResolver->getType($expr);

        if ($exprType instanceof GenericClassStringType) {
            return $exprType->getGenericType();
        }

        if ($exprType instanceof ConstantStringType) {
            if ($this->reflectionProvider->hasClass($exprType->getValue())) {
                return new ObjectType($exprType->getValue());
            }

            return new StringType();
        }

        return null;
    }
}
