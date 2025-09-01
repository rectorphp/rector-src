<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\BooleanAnd;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\NodeManipulator\BinaryOpManipulator;
use Rector\Php71\ValueObject\TwoNodeMatch;
use Rector\Rector\AbstractRector;
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
    public function __construct(
        private readonly BinaryOpManipulator $binaryOpManipulator
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

        $args = $funcCall->getArgs();
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
        $decodeMatch = null;
        $errorMatch = null;

        // match: json_decode(...) !== null   OR   null !== json_decode(...)
        if ($booleanAnd->left instanceof NotIdentical) {
            $decodeMatch = $this->decodeMatch($booleanAnd->left);
        }

        if ($booleanAnd->right instanceof NotIdentical) {
            $decodeMatch = $this->decodeMatch($booleanAnd->right);
        }

        if (! $decodeMatch instanceof TwoNodeMatch) {
            return null;
        }

        // match: json_last_error() === JSON_ERROR_NONE   OR   JSON_ERROR_NONE === json_last_error()
        if ($booleanAnd->right instanceof Identical) {
            $errorMatch = $this->errorMatch($booleanAnd->right);
        }
        if ($booleanAnd->left instanceof Identical) {
            $errorMatch = $this->errorMatch($booleanAnd->left);
        }

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

    protected function decodeMatch(NotIdentical $notIdentical): ?TwoNodeMatch
    {
        return $this->binaryOpManipulator->matchFirstAndSecondConditionNode(
            $notIdentical,
            fn ($node) => $node instanceof FuncCall && $this->isName($node->name, 'json_decode'),
            fn ($node) => $node instanceof ConstFetch && $this->isName($node->name, 'null')
        );
    }

    protected function errorMatch(Identical $identical): ?TwoNodeMatch
    {
        return $this->binaryOpManipulator->matchFirstAndSecondConditionNode(
            $identical,
            fn ($node) => $node instanceof FuncCall && $this->isName($node->name, 'json_last_error'),
            fn ($node) => $node instanceof ConstFetch && $this->isName($node->name, 'JSON_ERROR_NONE')
        );
    }
}
