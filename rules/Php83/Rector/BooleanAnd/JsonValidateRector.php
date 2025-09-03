<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\BooleanAnd;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\NodeManipulator\BinaryOpManipulator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\Php71\ValueObject\TwoNodeMatch;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\ValueObject\PolyfillPackage;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\JsonValidateRectorTest
 */
final class JsonValidateRector extends AbstractRector implements MinPhpVersionInterface, RelatedPolyfillInterface
{
    protected const ARG_NAMES = ['json', 'associative', 'depth', 'flags'];

    private const JSON_MAX_DEPTH = 0x7FFFFFFF;

    public function __construct(
        private readonly BinaryOpManipulator $binaryOpManipulator,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ArgsAnalyzer $argsAnalyzer,
        private ValueResolver $valueResolver,
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::JSON_VALIDATE;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace json_decode($json, true) !== null && json_last_error() === JSON_ERROR_NONE  with json_validate()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
if (json_decode($json, true) !== null && json_last_error() === JSON_ERROR_NONE) {
}

CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
if (json_validate($json)) {
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
        return [BooleanAnd::class];
    }

    /**
     * @param BooleanAnd $node
     */
    public function refactor(Node $node): ?Node
    {
        $funcCall = $this->matchJsonValidateArg($node);

        if (! $funcCall instanceof FuncCall) {
            return null;
        }

        if ($funcCall->isFirstClassCallable()) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $args = $funcCall->getArgs();
        $positions = $this->argsAnalyzer->hasNamedArg($args)
            ? $this->resolveNamedPositions($args)
            : $this->resolveOriginalPositions($funcCall, $scope);

        if ($positions === []) {
            return null;
        }

        if (! $this->validateArgs($args, $positions)) {
            return null;
        }
        $funcCall->name = new Name('json_validate');
        $funcCall->args = $args;

        return $funcCall;
    }

    public function providePolyfillPackage(): string
    {
        return PolyfillPackage::PHP_83;
    }

    public function matchJsonValidateArg(BooleanAnd $booleanAnd): ?FuncCall
    {
        // match: json_decode(...) !== null   OR   null !== json_decode(...)
        if (! ($booleanAnd->left instanceof NotIdentical)) {
            return null;
        }

        $decodeMatch = $this->binaryOpManipulator->matchFirstAndSecondConditionNode(
            $booleanAnd->left,
            fn ($node) => $node instanceof FuncCall && $this->isName($node->name, 'json_decode'),
            fn ($node) => $node instanceof ConstFetch && $this->isName($node->name, 'null')
        );

        if (! $decodeMatch instanceof TwoNodeMatch) {
            return null;
        }

        // match: json_last_error() === JSON_ERROR_NONE   OR   JSON_ERROR_NONE === json_last_error()
        if (! ($booleanAnd->right instanceof Identical)) {
            return null;
        }

        $errorMatch = $this->binaryOpManipulator->matchFirstAndSecondConditionNode(
            $booleanAnd->right,
            fn ($node) => $node instanceof FuncCall && $this->isName($node->name, 'json_last_error'),
            fn ($node) => $node instanceof ConstFetch && $this->isName($node->name, 'JSON_ERROR_NONE')
        );

        if (! $errorMatch instanceof TwoNodeMatch) {
            return null;
        }

        // always return the json_decode(...) call
        $funcCall = $decodeMatch->getFirstExpr();
        if (! $funcCall instanceof FuncCall) {
            return null;
        }
        return $funcCall;
    }

    /**
     * @param Arg[] $args
     * @param int[]|string[] $positions
     */
    protected function validateArgs(array $args, array $positions): bool
    {
        foreach ($positions as $position) {
            $arg = $args[$position] ?? '';
            if ($arg instanceof Arg && $arg->name instanceof Identifier && $arg->name->toString() === 'flags') {
                $flags = $this->valueResolver->getValue($arg);
                if ($flags !== JSON_INVALID_UTF8_IGNORE) {
                    return false;
                }
            }
            if ($arg instanceof Arg && $arg->name instanceof Identifier && $arg->name->toString() === 'depth') {
                $depth = $this->valueResolver->getValue($arg);
                if ($depth <= 0) {
                    return false;
                }
                if ($depth > self::JSON_MAX_DEPTH) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param Arg[] $args
     * @return int[]|string[]
     */
    private function resolveNamedPositions(array $args): array
    {
        $positions = [];

        foreach ($args as $position => $arg) {
            if (! $arg->name instanceof Identifier) {
                continue;
            }

            if (! $this->isNames($arg->name, self::ARG_NAMES)) {
                continue;
            }

            $positions[] = $position;
        }

        return $positions;
    }

    /**
     * @return int[]|string[]
     */
    private function resolveOriginalPositions(FuncCall $funcCall, Scope $scope): array
    {
        $functionReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($funcCall);
        if (! $functionReflection instanceof NativeFunctionReflection) {
            return [];
        }

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select(
            $functionReflection,
            $funcCall,
            $scope
        );

        $positions = [];

        foreach ($parametersAcceptor->getParameters() as $position => $parameterReflection) {
            if (in_array($parameterReflection->getName(), self::ARG_NAMES, true)) {
                $positions[] = $position;
            }
        }

        return $positions;
    }
}
