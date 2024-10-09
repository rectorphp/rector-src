<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassLike;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\Enum\ClassName;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassLike\RemoveMockObjectPropertyAnnotationRector\RemoveMockObjectPropertyAnnotationRectorTest
 */
final class RemoveMockObjectPropertyAnnotationRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const MOCK_OBJECT_CLASS = 'PHPUnit\Framework\MockObject\MockObject';

    public function __construct(
        private VarTagRemover $varTagRemover,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove @var annotation for PHPUnit\Framework\MockObject\MockObject combined with native object type, to fix reports by PHPStan',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class SomeTest extends TestCase
{
    /**
     * @var SomeClass|MockObject
     */
    private MockObject $someProperty;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class SomeTest extends TestCase
{
    private MockObject $someProperty;
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node)
    {
        if (! $this->isObjectType($node, new ObjectType(ClassName::TEST_CASE_CLASS))) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getProperties() as $property) {
            // not yet typed
            if (! $property->type instanceof Node) {
                continue;
            }

            if (count($property->props) !== 1) {
                continue;
            }

            if (! $this->isObjectType($property->type, new ObjectType(self::MOCK_OBJECT_CLASS))) {
                continue;
            }

            // clear var docblock
            if ($this->varTagRemover->removeVarTag($property)) {
                $hasChanged = true;
            }
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_PROPERTIES;
    }
}
