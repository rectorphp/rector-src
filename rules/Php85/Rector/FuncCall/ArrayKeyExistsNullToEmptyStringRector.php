<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ArrayKeyExistsNullToEmptyStringRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace null key in array_key_exists with empty string',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
        array_key_exists($key, $array);
        CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
        array_key_exists($key ?? '', $array);
        CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {

        if ($node instanceof FuncCall && ! $this->isName($node->name, 'array_key_exists')) {
            return null;
        }

        $args = $node->getArgs();

        if (count($args) < 2) {
            return null;
        }

        $keyExpr = $args[0]->value;

        if ($keyExpr instanceof Coalesce) {
            return null;
        }
        

        if ($keyExpr instanceof String_ && $keyExpr->value === '') {
            return null;
        }

        if ($this->nodeNameResolver->isName($keyExpr, 'null')) {
            $args[0]->value = new String_('');
            return $node;
        }

        if (! $keyExpr instanceof Coalesce && ! ($keyExpr instanceof String_ && $keyExpr->value === '')) {
            $args[0]->value = new Coalesce($keyExpr, new String_(''));
            return $node;
        }
        
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_NULL_ARG_IN_ARRAY_KEY_EXISTS_FUNCTION;
    }
}
