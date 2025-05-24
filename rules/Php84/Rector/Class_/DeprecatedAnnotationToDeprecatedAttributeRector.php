<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\DeprecatedTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector\DeprecatedAnnotationToDeprecatedAttributeRectorTest
 */
final class DeprecatedAnnotationToDeprecatedAttributeRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @see https://regex101.com/r/HL3OAT/1
     */
    private const VERSION_MATCH_REGEX = '/^(?:(\d+\.\d+\.\d+)\s+)?(.*)$/';

    public function __construct(
        private readonly PhpDocTagRemover $phpDocTagRemover,
        private readonly PhpAttributeGroupFactory $phpAttributeGroupFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change @deprecated annotation to Deprecated attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
/**
 * @deprecated 1.0.0 Use SomeOtherClass instead
 */
class SomeClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[\Deprecated(message: 'Use SomeOtherClass instead', since: '1.0.0')]
class SomeClass
{
}
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
/**
 * @deprecated 1.0.0 Use SomeOtherFunction instead
 */
function someFunction()
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[\Deprecated(message: 'Use SomeOtherFunction instead', since: '1.0.0')]
function someFunction()
{
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Function_::class, ClassMethod::class, ClassConst::class];
    }

    /**
     * @param ClassConst|Function_|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {

        $hasChanged = false;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo instanceof PhpDocInfo) {
            $requiresAttributeGroups = $this->handleDeprecated($phpDocInfo);
            if ($requiresAttributeGroups !== []) {
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
                $node->attrGroups = array_merge($node->attrGroups, $requiresAttributeGroups);
                $this->removeDeprecatedAnnotations($phpDocInfo);
                $hasChanged = true;
            }
        }

        return $hasChanged ? $node : null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATED_ATTRIBUTE;
    }

    /**
     * @return list<AttributeGroup>
     */
    private function handleDeprecated(PhpDocInfo $phpDocInfo): array
    {
        $attributeGroups = [];
        $desiredTagValueNodes = $phpDocInfo->getTagsByName('deprecated');
        foreach ($desiredTagValueNodes as $desiredTagValueNode) {
            if (! $desiredTagValueNode->value instanceof DeprecatedTagValueNode) {
                continue;
            }

            $attributeGroups[0] = $this->createAttributeGroup($desiredTagValueNode->value->description);
            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $desiredTagValueNode);
        }

        return $attributeGroups;
    }

    private function createAttributeGroup(string $annotationValue): AttributeGroup
    {
        preg_match(self::VERSION_MATCH_REGEX, $annotationValue, $matches);

        $since = $matches[1] ?? null;
        $message = $matches[2] ?? null;

        return $this->phpAttributeGroupFactory->createFromClassWithItems('Deprecated', array_filter([
            'message' => $message,
            'since' => $since,
        ]));
    }

    private function removeDeprecatedAnnotations(PhpDocInfo $phpDocInfo): bool
    {
        $hasChanged = false;

        $desiredTagValueNodes = $phpDocInfo->getTagsByName('deprecated');
        foreach ($desiredTagValueNodes as $desiredTagValueNode) {
            if (! $desiredTagValueNode->value instanceof GenericTagValueNode) {
                continue;
            }

            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $desiredTagValueNode);
            $hasChanged = true;
        }

        return $hasChanged;
    }
}
