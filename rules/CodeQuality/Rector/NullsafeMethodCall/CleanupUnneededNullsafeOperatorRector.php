<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\NullsafeMethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\TypeAnalyzer\ReturnStrictTypeAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/nullsafe_operator
 *
 * @see \Rector\Tests\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector\CleanupUnneededNullsafeOperatorRectorTest
 */
final class CleanupUnneededNullsafeOperatorRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReturnStrictTypeAnalyzer $returnStrictTypeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Cleanup unneeded nullsafe operator',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class HelloWorld {
    public function getString(): string
    {
         return 'hello world';
    }
}

function get(): HelloWorld
{
     return new HelloWorld();
}

echo get()?->getHelloWorld();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class HelloWorld {
    public function getString(): string
    {
         return 'hello world';
    }
}

function get(): HelloWorld
{
     return new HelloWorld();
}

echo get()->getHelloWorld();
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
        return [NullsafeMethodCall::class];
    }

    /**
     * @param NullsafeMethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->var instanceof MethodCall) {
            return null;
        }

        $returnNode = $this->returnStrictTypeAnalyzer->resolveMethodCallReturnNode($node->var);

        if (! $returnNode instanceof Node) {
            return null;
        }

        // Remove not needed Nullsafe for method call.
        $node = $node->var;

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULLSAFE_OPERATOR;
    }
}
