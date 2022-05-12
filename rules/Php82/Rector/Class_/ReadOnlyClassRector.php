<?php

declare(strict_types=1);

namespace Rector\Php82\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Core\ValueObject\Visibility;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/readonly_classes
 */
final class ReadOnlyClassRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const ATTRIBUTE = 'AllowDynamicProperties';

    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Decorate read-only class with `readonly` attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function __construct(
        private readonly string $name
    ) {
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final readonly class SomeClass
{
    public function __construct(
        private string $name
    ) {
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $this->visibilityManipulator->changeNodeVisibility($node, Visibility::READONLY);

        // update all properties, both in defined property or in property promotino to not readonly, as class already readonly

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::READONLY_CLASS;
    }

    private function shouldSkip(Class_ $class): bool
    {
        if ($this->visibilityManipulator->hasVisibility($class, Visibility::READONLY)) {
            return true;
        }

        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        $properties = $class->getProperties();
        foreach ($properties as $property) {
            if (! $property->isReadonly()) {
                return true;
            }
        }
        // property promotion
        return $this->phpAttributeAnalyzer->hasPhpAttribute($class, self::ATTRIBUTE);
    }
}
