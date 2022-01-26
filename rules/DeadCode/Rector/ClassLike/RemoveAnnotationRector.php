<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassLike;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassLike\RemoveAnnotationRector\RemoveAnnotationRectorTest
 */
final class RemoveAnnotationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const ANNOTATIONS_TO_REMOVE = 'annotations_to_remove';

    /**
     * @var string[]
     */
    private array $annotationsToRemove = [];

    public function __construct(
        private readonly PhpDocTagRemover $phpDocTagRemover
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove annotation by names', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
/**
 * @method getName()
 */
final class SomeClass
{
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
final class SomeClass
{
}
CODE_SAMPLE
,
                ['method'],
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassLike::class, FunctionLike::class, Property::class, ClassConst::class];
    }

    /**
     * @param ClassLike|FunctionLike|Property|ClassConst $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->annotationsToRemove === []) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        foreach ($this->annotationsToRemove as $annotationToRemove) {
            $this->phpDocTagRemover->removeByName($phpDocInfo, $annotationToRemove);
            if (! is_a($annotationToRemove, PhpDocTagValueNode::class, true)) {
                continue;
            }

            $phpDocInfo->removeByType($annotationToRemove);
        }

        if ($phpDocInfo->hasChanged()) {
            $node->setAttribute(AttributeKey::HAS_PHP_DOC_INFO_JUST_CHANGED, true);
            return $node;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $annotationsToRemove = $configuration[self::ANNOTATIONS_TO_REMOVE] ?? $configuration;
        Assert::isArray($annotationsToRemove);
        Assert::allString($annotationsToRemove);

        $this->annotationsToRemove = $annotationsToRemove;
    }
}
