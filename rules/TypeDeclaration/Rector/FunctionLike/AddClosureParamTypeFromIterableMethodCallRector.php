<?php

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
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\AddClosureParamTypeFromObject;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class AddClosureParamTypeFromIterableMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var int
     */
    private const DEFAULT_CLOSURE_ARG_POSITION = 0;

    /**
     * @var AddClosureParamTypeFromObject[]
     */
    private array $addClosureParamTypeFromObjects;

    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    /**
     * @param AddClosureParamTypeFromObject[] $configuration
     * @return void
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AddClosureParamTypeFromObject::class);

        $this->addClosureParamTypeFromObjects = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            '',
            [
                new ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param Collection<string> $string
     */
    public function run(Collection $collection)
    {
        return $collection->map(function ($item) {
            return $item;
        });
    }
}
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param Collection<string> $string
     */
    public function run(Collection $collection)
    {
        return $collection->map(function (string $item) {
            return $item;
        });
    }
}
CODE_SAMPLE,
                [
                    new AddClosureParamTypeFromObject(new ObjectType('Collection'), 'map', 0, 0),
                ]
)
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->addClosureParamTypeFromObjects as $addClosureParamTypeFromObject) {
            if ($node instanceof MethodCall) {
                $caller = $node->var;
            } else {
                continue;
            }

            if (! $this->isCallMatch($caller, $addClosureParamTypeFromObject, $node)) {
                continue;
            }

            $type = $this->getType($caller)->getIterableValueType();

            return $this->processCallLike($node, $addClosureParamTypeFromObject, $type);
        }

        return null;
    }

    private function processCallLike(
        MethodCall $callLike,
        AddClosureParamTypeFromObject $addClosureParamTypeFromArg,
        Type $type
    ): ?MethodCall {
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

        $callLikeArg = $callLike->getArgs()[self::DEFAULT_CLOSURE_ARG_POSITION] ?? null;
        if (! $callLikeArg instanceof Arg) {
            return null;
        }

        $hasChanged = $this->refactorParameter(
            $functionLike->params[$addClosureParamTypeFromArg->getFunctionLikePosition()],
            $type,
        );

        if ($hasChanged) {
            return $callLike;
        }

        return null;
    }

    private function refactorParameter(Param $param, Type $type): bool
    {
        // already set → no change
        if ($param->type instanceof Node) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual($currentParamType, $type)) {
                return false;
            }
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::PARAM);
        $param->type = $paramTypeNode;

        return true;
    }

    private function isCallMatch(
        Name|Expr $name,
        AddClosureParamTypeFromObject $addClosureParamTypeFromArg,
        MethodCall $call
    ): bool {
        if (! $this->isObjectType($name, $addClosureParamTypeFromArg->getObjectType())) {
            return false;
        }

        return $this->isName($call->name, $addClosureParamTypeFromArg->getMethodName());
    }
}
