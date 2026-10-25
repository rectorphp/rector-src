<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\Property;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\MergePhpstanDocTagIntoNativeRectorTest
 */
final class MergePhpstanDocTagIntoNativeRector extends AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const array PHPSTAN_TAG_TO_NATIVE_TAG = [
        '@phpstan-var' => '@var',
        '@phpstan-param' => '@param',
        '@phpstan-return' => '@return',
    ];

    public function __construct(
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Merge more precise @phpstan-var/@phpstan-param/@phpstan-return docblock tag into its native @var/@param/@return tag',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var Collection
     *
     * @phpstan-var Collection<int, string>
     */
    private $items;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var Collection<int, string>
     */
    private $items;
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
        return [Property::class, Param::class, ClassConst::class, ClassMethod::class, Function_::class];
    }

    /**
     * @param Property|Param|ClassConst|ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // @phpstan-* tags can only live in a docblock; skip nodes without one
        if (! $node->getDocComment() instanceof Doc) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $phpDocNode = $phpDocInfo->getPhpDocNode();

        $phpstanTagKeys = [];
        $multilineTagKeys = [];
        $nativeDescriptions = [];

        foreach ($phpDocNode->children as $phpDocChildNode) {
            if (! $phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }

            $tagValueNode = $this->resolveMergeableTagValueNode($phpDocChildNode);
            if (! $tagValueNode instanceof PhpDocTagValueNode) {
                continue;
            }

            $tagKey = $this->resolveTagKey($tagValueNode);

            // skip multiline tag, as phpcs does not support multiline native tag yet
            if ($this->isMultiline($phpDocChildNode)) {
                $multilineTagKeys[] = $tagKey;
            }

            if (isset(self::PHPSTAN_TAG_TO_NATIVE_TAG[$phpDocChildNode->name])) {
                $phpstanTagKeys[] = $tagKey;
            } elseif ($tagValueNode->description !== '') {
                // remember native tag descriptions, so they survive when the native tag is overruled
                $nativeDescriptions[$tagKey] = $tagValueNode->description;
            }
        }

        $mergeableTagKeys = array_diff($phpstanTagKeys, $multilineTagKeys);
        if ($mergeableTagKeys === []) {
            return null;
        }

        foreach ($phpDocNode->children as $key => $phpDocChildNode) {
            if (! $phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }

            $tagValueNode = $this->resolveMergeableTagValueNode($phpDocChildNode);
            if (! $tagValueNode instanceof PhpDocTagValueNode) {
                continue;
            }

            $tagKey = $this->resolveTagKey($tagValueNode);
            if (! in_array($tagKey, $mergeableTagKeys, true)) {
                continue;
            }

            $nativeTagName = self::PHPSTAN_TAG_TO_NATIVE_TAG[$phpDocChildNode->name] ?? null;

            // drop the weaker native tag that the @phpstan-* variant overrules
            if ($nativeTagName === null) {
                unset($phpDocNode->children[$key]);
                continue;
            }

            // promote the @phpstan-* tag to its native counterpart, keeping the description of the overruled native tag
            if ($tagValueNode->description === '') {
                $tagValueNode->description = $nativeDescriptions[$tagKey] ?? '';
            }

            $phpDocNode->children[$key] = new PhpDocTagNode($nativeTagName, $tagValueNode);
        }

        $phpDocNode->children = array_values($phpDocNode->children);

        // drop a leading empty line left behind by a removed native tag
        while ($phpDocNode->children !== []) {
            $firstChildNode = $phpDocNode->children[0];
            if ($firstChildNode instanceof PhpDocTextNode && trim($firstChildNode->text) === '') {
                array_shift($phpDocNode->children);
                continue;
            }

            break;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    private function resolveMergeableTagValueNode(
        PhpDocTagNode $phpDocTagNode
    ): ParamTagValueNode|VarTagValueNode|ReturnTagValueNode|null {
        if (! isset(self::PHPSTAN_TAG_TO_NATIVE_TAG[$phpDocTagNode->name]) && ! in_array(
            $phpDocTagNode->name,
            self::PHPSTAN_TAG_TO_NATIVE_TAG,
            true
        )) {
            return null;
        }

        $tagValueNode = $phpDocTagNode->value;
        if ($tagValueNode instanceof ParamTagValueNode || $tagValueNode instanceof VarTagValueNode || $tagValueNode instanceof ReturnTagValueNode) {
            return $tagValueNode;
        }

        return null;
    }

    private function isMultiline(PhpDocTagNode $phpDocTagNode): bool
    {
        $startLine = $phpDocTagNode->getAttribute(Attribute::START_LINE);
        $endLine = $phpDocTagNode->getAttribute(Attribute::END_LINE);

        return is_int($startLine) && is_int($endLine) && $endLine > $startLine;
    }

    private function resolveTagKey(ParamTagValueNode|VarTagValueNode|ReturnTagValueNode $tagValueNode): string
    {
        if ($tagValueNode instanceof ParamTagValueNode) {
            return '@param ' . $tagValueNode->parameterName;
        }

        if ($tagValueNode instanceof VarTagValueNode) {
            return '@var ' . $tagValueNode->variableName;
        }

        return '@return';
    }
}
