<?php

declare(strict_types=1);

namespace Rector\Php82\Rector\Encapsed;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\String_;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/deprecate_dollar_brace_string_interpolation
 *
 * @see \Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\VariableInStringInterpolationFixerRectorTest
 */
final class VariableInStringInterpolationFixerRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace deprecated "${var}" to "{$var}"', [
            new CodeSample(
                <<<'CODE_SAMPLE'
echo "${var}";
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
echo "{$var}";
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Encapsed::class];
    }

    /**
     * @param Encapsed $node
     */
    public function refactor(Node $node): ?Node
    {
        $kind = $node->getAttribute(AttributeKey::KIND);

        // variable in single quoted is escaped, print as is
        if ($kind !== String_::KIND_DOUBLE_QUOTED) {
            return null;
        }

        $oldTokens = $this->file->getOldTokens();
        $hasChanged = false;
        foreach ($node->parts as $part) {
            if (! $part instanceof Variable) {
                continue;
            }

            $startTokenPos = $part->getStartTokenPos();

            if (! isset($oldTokens[$startTokenPos]) ) {
                continue;
            }

            if (! is_array($oldTokens[$startTokenPos])) {
                continue;
            }

            if ($oldTokens[$startTokenPos][1] !== '${') {
                continue;
            }

            $part->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_VARIABLE_IN_STRING_INTERPOLATION;
    }
}
