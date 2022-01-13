<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast\String_ as CastString_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
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
        if ($this->argsAnalyzer->hasNamedArg($args)) {
            return null;
        }

        $originalPosition = $this->resolveOriginalPosition($node);
        $argValue = $args[$originalPosition]->value;

        if ($argValue instanceof ConstFetch && $this->valueResolver->isNull($argValue)) {
            $args[$originalPosition]->value = new String_('');
            $node->args = $args;

            return $node;
        }

        if ($args[$originalPosition]->value instanceof MethodCall) {
            $trait = $this->betterNodeFinder->findParentType($node, Trait_::class);
            if ($trait instanceof Trait_) {
                return null;
            }
        }

        $type = $this->nodeTypeResolver->getType($args[$originalPosition]->value);
        if ($this->nodeTypeAnalyzer->isStringyType($type)) {
            return null;
        }

        if ($this->isItsScopeHasParentScope($args[$originalPosition]->value)) {
            return null;
        }

        $args[$originalPosition]->value = new CastString_($args[$originalPosition]->value);
        $node->args = $args;

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_NULL_ARG_IN_STRING_FUNCTION;
    }

    private function isItsScopeHasParentScope(Expr $expr): bool
    {
        $scope = $expr->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $parentScope = $scope->getParentScope();
        return $parentScope instanceof Scope;
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
