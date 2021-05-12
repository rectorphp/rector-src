<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Renaming\Rector\StaticCall\RenameStaticMethodRector\RenameStaticMethodRectorTest
 */
final class RenameStaticMethodRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const OLD_TO_NEW_METHODS_BY_CLASSES = 'old_to_new_method_by_classes';

    /**
     * @var string
     */
    private const SOME_CLASS = 'SomeClass';

    /**
     * @var RenameStaticMethod[]
     */
    private array $staticMethodRenames = [];

    public function getRuleDefinition(): RuleDefinition
    {
        $renameClassConfiguration = [
            self::OLD_TO_NEW_METHODS_BY_CLASSES => [
                new RenameStaticMethod(self::SOME_CLASS, 'oldMethod', 'AnotherExampleClass', 'newStaticMethod'),
            ],
        ];

        $renameMethodConfiguration = [
            self::OLD_TO_NEW_METHODS_BY_CLASSES => [
                new RenameStaticMethod(self::SOME_CLASS, 'oldMethod', self::SOME_CLASS, 'newStaticMethod'),
            ],
        ];

        return new RuleDefinition('Turns method names to new ones.', [
            new ConfiguredCodeSample(
                'SomeClass::oldStaticMethod();',
                'AnotherExampleClass::newStaticMethod();',
                $renameClassConfiguration
            ),
            new ConfiguredCodeSample(
                'SomeClass::oldStaticMethod();',
                'SomeClass::newStaticMethod();',
                $renameMethodConfiguration
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->staticMethodRenames as $staticMethodRename) {
            if (! $this->isObjectType($node->class, $staticMethodRename->getOldObjectType())) {
                continue;
            }

            if (! $this->isName($node->name, $staticMethodRename->getOldMethod())) {
                continue;
            }

            return $this->rename($node, $staticMethodRename);
        }

        return null;
    }

    /**
     * @param array<string, RenameStaticMethod[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $oldToNewMethodsByClasses = $configuration[self::OLD_TO_NEW_METHODS_BY_CLASSES];
        Assert::allIsInstanceOf($oldToNewMethodsByClasses, RenameStaticMethod::class);
        $this->staticMethodRenames = $oldToNewMethodsByClasses;
    }

    private function rename(StaticCall $staticCall, RenameStaticMethod $renameStaticMethod): StaticCall
    {
        $staticCall->name = new Identifier($renameStaticMethod->getNewMethod());

        if ($renameStaticMethod->hasClassChanged()) {
            $staticCall->class = new FullyQualified($renameStaticMethod->getNewClass());
        }

        return $staticCall;
    }
}
