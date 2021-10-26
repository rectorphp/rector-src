<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/migration80.incompatible.php#migration80.incompatible.resource2object
 *
 * @see \Rector\Tests\Php80\Rector\FuncCall\Php8ResourceToObjectRector\Php8ResourceToObjectRector
 */
final class Php8ResourceToObjectRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var array
     */
    private const COLLECTION_FUNCTION_TO_RETURN_OBJECT = [
        'curl_init' => 'CurlHandle',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change is_resource() to instanceof Object',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $ch = curl_init();
        is_resource($ch);
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $ch = curl_init();
        $ch instanceof \CurlHandle;
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
        if (! $this->nodeNameResolver->isName($node, 'is_resource')) {
            return null;
        }

        if (! isset($node->args[0])) {
            return null;
        }

        if (! $node->args[0] instanceof Arg) {
            return null;
        }

        $objectInstanceCheck = null;
        $assign = $this->betterNodeFinder->findFirstPreviousOfNode($node, function (Node $subNode) use (&$objectInstanceCheck): bool {
            if (! $subNode instanceof Assign) {
                return false;
            }

            if (! $subNode->expr instanceof FuncCall) {
                return false;
            }

            foreach (self::COLLECTION_FUNCTION_TO_RETURN_OBJECT as $key => $value) {
                if ($this->nodeNameResolver->isName($subNode->expr, $key)) {
                    $objectInstanceCheck = $value;
                    return true;
                }
            }

            return false;
        });

        if (! $assign instanceof Assign) {
            return null;
        }

        return new Instanceof_($node->args[0], new FullyQualified($objectInstanceCheck));
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PHP8_RESOURCE_TO_OBJECT;
    }
}
