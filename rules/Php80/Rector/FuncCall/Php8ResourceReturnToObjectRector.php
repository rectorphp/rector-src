<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/migration80.incompatible.php#migration80.incompatible.resource2object
 *
 * @see \Rector\Tests\Php80\Rector\FuncCall\Php8ResourceReturnToObjectRector\Php8ResourceReturnToObjectRectorTest
 */
final class Php8ResourceReturnToObjectRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var array<string, string>
     */
    private const COLLECTION_FUNCTION_TO_RETURN_OBJECT = [
        // curl
        'CurlHandle',
        'CurlMultiHandle',
        'CurlShareHandle',
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var Expr $argResourceValue */
        $argResourceValue = $node->args[0]->value;
        $argValueType = $this->nodeTypeResolver->getType($argResourceValue);
        if (! $argValueType instanceof FullyQualifiedObjectType) {
            return null;
        }

        $objectInstanceCheck = $this->resolveObjectInstanceCheck($argValueType);
        if ($objectInstanceCheck === null) {
            return null;
        }

        if (! $this->isDoubleCheck($node, $argResourceValue, $objectInstanceCheck)) {
            return new Instanceof_($argResourceValue, new FullyQualified($objectInstanceCheck));
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PHP8_RESOURCE_TO_OBJECT;
    }

    private function resolveObjectInstanceCheck(FullyQualifiedObjectType $fullyQualifiedObjectType): ?string
    {
        $className = $fullyQualifiedObjectType->getClassName();
        foreach (self::COLLECTION_FUNCTION_TO_RETURN_OBJECT as $value) {
            if ($className === $value) {
                return $value;
            }
        }

        return null;
    }

    private function isDoubleCheck(FuncCall $funcCall, Expr $expr, string $objectInstanceCheck): bool
    {
        $binaryOp = $this->betterNodeFinder->findParentType($funcCall, BinaryOp::class);
        if (! $binaryOp instanceof BinaryOp) {
            return false;
        }

        return (bool) $this->betterNodeFinder->findFirst($binaryOp, function (Node $subNode) use (
            $objectInstanceCheck,
            $expr
        ): bool {
            if (! $subNode instanceof Instanceof_) {
                return false;
            }

            if (! $subNode->class instanceof FullyQualified) {
                return false;
            }

            if (! $this->nodeComparator->areNodesEqual($expr, $subNode->expr)) {
                return false;
            }

            return $this->nodeNameResolver->isName($subNode->class, $objectInstanceCheck);
        });
    }

    private function shouldSkip(FuncCall $funcCall): bool
    {
        if (! $this->nodeNameResolver->isName($funcCall, 'is_resource')) {
            return true;
        }

        if (! isset($funcCall->args[0])) {
            return true;
        }

        $argResource = $funcCall->args[0];
        return ! $argResource instanceof Arg;
    }
}
