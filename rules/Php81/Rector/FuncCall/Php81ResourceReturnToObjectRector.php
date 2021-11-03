<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\NodeManipulator\ResourceReturnToObject;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/migration81.incompatible.php#migration81.incompatible.resource2object
 *
 * @see \Rector\Tests\Php81\Rector\FuncCall\Php81ResourceReturnToObjectRector\Php81ResourceReturnToObjectRectorTest
 */
final class Php81ResourceReturnToObjectRector extends AbstractRector implements MinPhpVersionInterface
{
        /**
     * @var array<string, string>
     */
    private const COLLECTION_FUNCTION_TO_RETURN_OBJECT = [
        // finfo
        'finfo_open' => 'finfo',
    ];

    public function __construct(private ResourceReturnToObject $resourceReturnToObject)
    {
    }

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
        $f = finfo_open();
        is_resource($f);
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $f = finfo_open();
        $f instanceof \finfo;
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
        return [FuncCall::class, BooleanOr::class];
    }

    /**
     * @param FuncCall|BooleanOr $node
     */
    public function refactor(Node $node): ?Node
    {
        return $this->resourceReturnToObject->refactor($node, self::COLLECTION_FUNCTION_TO_RETURN_OBJECT);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PHP81_RESOURCE_TO_OBJECT;
    }
}
