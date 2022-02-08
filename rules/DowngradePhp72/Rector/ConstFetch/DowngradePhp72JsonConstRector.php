<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\Rector\ConstFetch;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/function.json-encode.php#refsect1-function.json-encode-changelog
 *
 * @see \Rector\Tests\DowngradePhp72\Rector\ConstFetch\DowngradePhp72JsonConstRector\DowngradePhp72JsonConstRectorTest
 */
final class DowngradePhp72JsonConstRector extends AbstractRector
{
    /**
     * @var array<string>
     */
    private const CONSTANTS = ['JSON_INVALID_UTF8_IGNORE', 'JSON_INVALID_UTF8_SUBSTITUTE'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change Json constant that available only in php 7.2 to 0',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$inDecoder = new Decoder($connection, true, 512, \JSON_INVALID_UTF8_IGNORE);
$inDecoder = new Decoder($connection, true, 512, \JSON_INVALID_UTF8_SUBSTITUTE);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$inDecoder = new Decoder($connection, true, 512, 0);
$inDecoder = new Decoder($connection, true, 512, 0);
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
        return [ConstFetch::class, BitwiseOr::class];
    }

    /**
     * @param ConstFetch|BitwiseOr $node
     */
    public function refactor(Node $node): ?Node
    {
        $zeroConstFetch = new ConstFetch(new Name('0'));

        if ($node instanceof ConstFetch) {
            if (! $this->nodeNameResolver->isNames($node, self::CONSTANTS)) {
                return null;
            }

            $parent = $node->getAttribute(AttributeKey::PARENT_NODE);
            if (! $parent instanceof BitwiseOr) {
                return $zeroConstFetch;
            }

            return null;
        }

        return $this->processBitwiseOr($node, $zeroConstFetch);
    }

    private function processBitwiseOr(BitwiseOr $bitwiseOr, ConstFetch $zeroConstFetch): ?Expr
    {
        if ($bitwiseOr->left instanceof ConstFetch && $this->nodeNameResolver->isNames($bitwiseOr->left, self::CONSTANTS)) {
            $bitwiseOr->left = $zeroConstFetch;
        }

        if ($bitwiseOr->right instanceof ConstFetch && $this->nodeNameResolver->isNames($bitwiseOr->right, self::CONSTANTS)) {
            $bitwiseOr->right = $zeroConstFetch;
        }

        if ($this->nodeComparator->areNodesEqual($bitwiseOr->left, $zeroConstFetch) && $this->nodeComparator->areNodesEqual($bitwiseOr->right, $zeroConstFetch)) {
            return $zeroConstFetch;
        }

        if ($this->nodeComparator->areNodesEqual($bitwiseOr->left, $zeroConstFetch)) {
            return $bitwiseOr->right;
        }

        if ($this->nodeComparator->areNodesEqual($bitwiseOr->right, $zeroConstFetch)) {
            return $bitwiseOr->left;
        }

        return null;
    }
}
