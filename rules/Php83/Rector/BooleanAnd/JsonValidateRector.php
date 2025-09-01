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
        $funcCall->args = [];
        $funcCall->args = $args;

        return $funcCall;
    }

    public function providePolyfillPackage(): string
    {
        return PolyfillPackage::PHP_83;
    }

    public function matchJsonValidateArg(BooleanAnd $booleanAnd): ?FuncCall
    {
        if ($booleanAnd->left instanceof NotIdentical) {
            $notIdentical = $booleanAnd->left;

            $jsonDecodeCall = $this->getJsonNode($notIdentical);

            if (! $booleanAnd->right instanceof Identical) {
                return null;
            }

            $identical = $booleanAnd->right;

            if ($identical->left instanceof \PhpParser\Node\Expr\FuncCall
                && $this->isName($identical->left->name, 'json_last_error')
                && $identical->right instanceof ConstFetch
                && $this->isName($identical->right->name, 'JSON_ERROR_NONE')) {
                return $jsonDecodeCall; // return json_decode(...) call
            }

            if (
                $identical->left instanceof ConstFetch
                && $this->isName($identical->left->name, 'JSON_ERROR_NONE')
                && $identical->right instanceof \PhpParser\Node\Expr\FuncCall
                && $this->isName($identical->right->name, 'json_last_error')
            ) {
                return $jsonDecodeCall; // return json_decode(...) call
            }

        }
        return null;
    }

    protected function getJsonNode(NotIdentical $notIdentical): ?FuncCall
    {

        if ($notIdentical->left instanceof FuncCall
            && $this->isName($notIdentical->left->name, 'json_decode')
        ) {
            return $notIdentical->left;
        }

        if ($notIdentical->right instanceof FuncCall
        && $this->isName($notIdentical->right->name, 'json_decode')
        ) {
            return $notIdentical->right;
        }

        return null;
    }
}
