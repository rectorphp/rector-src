<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast\String_ as CastString_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\NodeAnalyzer\ArgsAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php73\NodeTypeAnalyzer\NodeTypeAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\NullToStrictStringFuncCallArgRectorTest
 */
final class NullToStrictStringFuncCallArgRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var array<string, string>
     */
    private const ARG_POSITION_NAME_NULL_TO_STRICT_STRING = [
        'preg_split' => 'subject',
        'preg_match' => 'subject',
        'preg_match_all' => 'subject',
        'explode' => 'string',
        'strlen' => 'string',
    ];

    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly NodeTypeAnalyzer $nodeTypeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change null to strict string defined function call args',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        preg_split("#a#", null);
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        preg_split("#a#", '');
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $args = $node->getArgs();
        $position = $this->argsAnalyzer->hasNamedArg($args)
            ? $this->resolveNamedPosition($node, $args)
            : $this->resolveOriginalPosition($node);

        return $this->processNullToStrictStringOnNodePosition($node, $args, $position);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_NULL_ARG_IN_STRING_FUNCTION;
    }

    /**
     * @param Arg[] $args
     */
    private function resolveNamedPosition(FuncCall $funcCall, array $args): ?int
    {
        $functionName = $this->nodeNameResolver->getName($funcCall);
        $argName = self::ARG_POSITION_NAME_NULL_TO_STRICT_STRING[$functionName];

        foreach ($args as $position => $arg) {
            if (!$arg->name instanceof Identifier) {
                continue;
            }

            if (!$this->nodeNameResolver->isName($arg->name, $argName)) {
                continue;
            }

            return $position;
        }

        return null;
    }

    /**
     * @param Arg[] $args
     */
    private function processNullToStrictStringOnNodePosition(FuncCall $funcCall, array $args, ?int $position): ?FuncCall
    {
        if (! is_int($position)) {
            return null;
        }

        $argValue = $args[$position]->value;

        if ($argValue instanceof ConstFetch && $this->valueResolver->isNull($argValue)) {
            $args[$position]->value = new String_('');
            $funcCall->args = $args;

            return $funcCall;
        }

        $type = $this->nodeTypeResolver->getType($args[$position]->value);
        if ($this->nodeTypeAnalyzer->isStringyType($type)) {
            return null;
        }

        if ($this->isAnErrorTypeFromParentScope($args[$position]->value, $type)) {
            return null;
        }

        if ($args[$position]->value instanceof MethodCall) {
            $trait = $this->betterNodeFinder->findParentType($funcCall, Trait_::class);
            if ($trait instanceof Trait_) {
                return null;
            }
        }

        $args[$position]->value = new CastString_($args[$position]->value);
        $funcCall->args = $args;

        return $funcCall;
    }

    private function isAnErrorTypeFromParentScope(Expr $expr, Type $type): bool
    {
        if (! $type instanceof MixedType) {
            return false;
        }

        $scope = $expr->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $parentScope = $scope->getParentScope();
        if ($parentScope instanceof Scope) {
            return $parentScope->getType($expr) instanceof ErrorType;
        }

        return false;
    }

    private function resolveOriginalPosition(FuncCall $funcCall): ?int
    {
        $functionReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($funcCall);
        if (! $functionReflection instanceof NativeFunctionReflection) {
            return null;
        }

        $parametersAcceptor = ParametersAcceptorSelector::selectSingle($functionReflection->getVariants());
        $functionName = $this->nodeNameResolver->getName($funcCall);
        $argName = self::ARG_POSITION_NAME_NULL_TO_STRICT_STRING[$functionName];

        foreach ($parametersAcceptor->getParameters() as $position => $parameterReflection) {
            if ($parameterReflection->getName() === $argName) {
                return $position;
            }
        }

        return null;
    }

    private function shouldSkip(FuncCall $funcCall): bool
    {
        $functionNames = array_keys(self::ARG_POSITION_NAME_NULL_TO_STRICT_STRING);
        return ! $this->nodeNameResolver->isNames($funcCall, $functionNames);
    }
}
