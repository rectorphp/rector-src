<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\FunctionLike;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Return_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\FunctionLike\ConvertMultilineDocblockToSingleLineRector\ConvertMultilineDocblockToSingleLineRectorTest
 */
final class ConvertMultilineDocblockToSingleLineRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert multi-line docblock with only single statement to single line',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @return string
 */
public function getTitle(): string
{
    /**
     * @var string $name
     */
    $name = 'test';
    
    return $name;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
/** @return string */
public function getTitle(): string
{
    /** @var string $name */
    $name = 'test';
    
    return $name;
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
        return [
            Class_::class,
            ClassMethod::class,
            Function_::class,
            Property::class,
            Expression::class,
            Echo_::class,
            Return_::class,
            If_::class,
            Foreach_::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        if (! $this->shouldConvertToSingleLine($phpDocInfo)) {
            return null;
        }

        $originalDocText = $docComment->getText();
        $inlinedDocText = $this->convertToSingleLine($originalDocText);

        if ($originalDocText === $inlinedDocText) {
            return null;
        }

        $node->setDocComment(new Doc($inlinedDocText));

        return $node;
    }

    private function convertToSingleLine(string $docComment): string
    {
        $content = trim($docComment);
        $content = substr($content, 3); // Remove "/**"
        $content = substr($content, 0, -2); // Remove "*/"

        $lines = preg_split('/\r\n|\r|\n/', $content) ?: [];
        $cleanedLines = [];

        foreach ($lines as $line) {
            $line = trim($line);
            $line = preg_replace('/^\*\s?/', '', $line) ?: '';
            $line = trim($line);

            if ($line !== '') {
                $cleanedLines[] = $line;
            }
        }

        $inlineContent = implode(' ', $cleanedLines);

        return '/** ' . $inlineContent . ' */';
    }

    private function shouldConvertToSingleLine(PhpDocInfo $phpDocInfo): bool
    {
        $phpDocNode = $phpDocInfo->getPhpDocNode();
        $children = $phpDocNode->children;

        $tagCount = 0;
        $hasTextContent = false;

        foreach ($children as $child) {
            if ($child instanceof PhpDocTagNode) {
                ++$tagCount;
            } elseif ($child instanceof PhpDocTextNode) {
                $text = trim($child->text);
                if ($text !== '' && $text !== '*') {
                    $hasTextContent = true;
                }
            }
        }

        return $tagCount === 1 && ! $hasTextContent;
    }
}
