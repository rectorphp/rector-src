<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Include_;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Scalar\String_;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/symplify/CodingStandard#includerequire-should-be-followed-by-absolute-path
 *
 * @see \Rector\Tests\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector\AbsolutizeRequireAndIncludePathRectorTest
 */
final class AbsolutizeRequireAndIncludePathRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'include/require to absolute path. This Rector might introduce backwards incompatible code, when the include/require being changed depends on the current working directory.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        require 'autoload.php';

        require $variable;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        require __DIR__ . '/autoload.php';

        require $variable;
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
        return [Include_::class];
    }

    /**
     * @param Include_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->expr instanceof Concat && $node->expr->left instanceof String_ && $this->isRefactorableStringPath(
            $node->expr->left
        )) {
            $node->expr->left = $this->prefixWithDirConstant($node->expr->left);

            return $node;
        }

        if (! $node->expr instanceof String_) {
            return null;
        }

        if (! $this->isRefactorableStringPath($node->expr)) {
            return null;
        }

        /** @var string $includeValue */
        $includeValue = $this->valueResolver->getValue($node->expr);

        // skip phar
        if (\str_starts_with($includeValue, 'phar://')) {
            return null;
        }

        // skip absolute paths
        if (\str_starts_with($includeValue, '/')) {
            return null;
        }

        if (str_contains($includeValue, 'config/')) {
            return null;
        }

        // add preslash to string
        $node->expr->value = \str_starts_with($includeValue, './') ? Strings::substring(
            $includeValue,
            1
        ) : '/' . $includeValue;

        $node->expr = $this->prefixWithDirConstant($node->expr);

        return $node;
    }

    private function isRefactorableStringPath(String_ $string): bool
    {
        return ! \str_starts_with($string->value, 'phar://');
    }

    private function prefixWithDirConstant(String_ $string): Concat
    {
        $this->removeExtraDotSlash($string);
        $this->prependSlashIfMissing($string);

        return new Concat(new Dir(), $string);
    }

    /**
     * Remove "./" which would break the path
     */
    private function removeExtraDotSlash(String_ $string): void
    {
        if (! \str_starts_with($string->value, './')) {
            return;
        }

        $string->value = Strings::replace($string->value, '#^\.\/#', '/');
    }

    private function prependSlashIfMissing(String_ $string): void
    {
        if (\str_starts_with($string->value, '/')) {
            return;
        }

        $string->value = '/' . $string->value;
    }
}
