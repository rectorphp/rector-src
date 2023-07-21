<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\Printer;

use PhpParser\Node\Stmt\InlineHTML;
use PHPStan\PhpDocParser\Printer\Printer;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocNodeVisitor\ChangedPhpDocNodeVisitor;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;

/**
 * @see \Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter\PhpDocInfoPrinterTest
 */
final class PhpDocInfoPrinter
{
    /**
     * @var string
     * @see https://regex101.com/r/Ab0Vey/1
     */
    private const CLOSING_DOCBLOCK_REGEX = '#\*\/(\s+)?$#';

    /**
     * @var string
     * @see https://regex101.com/r/mVmOCY/2
     */
    private const OPENING_DOCBLOCK_REGEX = '#^(/\*\*)#';

    /**
     * @var string
     * @see https://regex101.com/r/LLWiPl/1
     */
    private const DOCBLOCK_START_REGEX = '#^(\/\/|\/\*\*|\/\*|\#)#';

    /**
     * @var string Uses a hardcoded unix-newline since most codes use it (even on windows) - otherwise we would need to normalize newlines
     */
    private const NEWLINE_WITH_ASTERISK = "\n" . ' *';

    public function __construct(
        private readonly DocBlockInliner $docBlockInliner,
        private readonly ChangedPhpDocNodeVisitor $changedPhpDocNodeVisitor,
        private readonly Printer $printer,
    ) {
        $changedPhpDocNodeTraverser = new PhpDocNodeTraverser();
        $changedPhpDocNodeTraverser->addPhpDocNodeVisitor($this->changedPhpDocNodeVisitor);
    }

    public function printNew(PhpDocInfo $phpDocInfo): string
    {
        $docContent = (string) $phpDocInfo->getPhpDocNode();
        if ($phpDocInfo->isSingleLine()) {
            return $this->docBlockInliner->inline($docContent);
        }

        if ($phpDocInfo->getNode() instanceof InlineHTML) {
            return '<?php' . PHP_EOL . $docContent . PHP_EOL . '?>';
        }

        return $docContent;
    }

    /**
     * As in php-parser
     *
     * ref: https://github.com/nikic/PHP-Parser/issues/487#issuecomment-375986259
     * - Tokens[node.startPos .. subnode1.startPos]
     * - Print(subnode1)
     * - Tokens[subnode1.endPos .. subnode2.startPos]
     * - Print(subnode2)
     * - Tokens[subnode2.endPos .. node.endPos]
     */
    public function printFormatPreserving(PhpDocInfo $phpDocInfo): string
    {
        if ($phpDocInfo->getTokens() === []) {
            // completely new one, just print string version of it
            if ($phpDocInfo->getPhpDocNode()->children === []) {
                return '';
            }

            if ($phpDocInfo->getNode() instanceof InlineHTML) {
                return '<?php' . PHP_EOL . $phpDocInfo->getPhpDocNode() . PHP_EOL . '?>';
            }

            return (string) $phpDocInfo->getPhpDocNode();
        }

        //$phpDocNode = $phpDocInfo->getPhpDocNode();
        return $this->printer->printFormatPreserving(
            $phpDocInfo->getPhpDocNode(),
            $phpDocInfo->getOriginalPhpDocNode(),
            $phpDocInfo->getTokenIterator()
        );

//        return $phpdocContents;
//
//        $this->tokens = $phpDocInfo->getTokens();
//
//        $this->tokenCount = $phpDocInfo->getTokenCount();
//        $this->phpDocInfo = $phpDocInfo;
//
//        $this->currentTokenPosition = 0;
//
//        $phpDocString = $this->printPhpDocNode($phpDocNode);
//
//        // hotfix of extra space with callable ()
//        return Strings::replace($phpDocString, self::CALLABLE_REGEX, 'callable(');
    }
}
