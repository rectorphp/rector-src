<?php

declare(strict_types=1);

namespace Rector\FileFormatter\Formatter;

use PrettyXml\Formatter;
use Rector\Core\ValueObject\Application\File;
use Rector\FileFormatter\Contract\Formatter\FileFormatterInterface;
use Rector\FileFormatter\ValueObject\EditorConfigConfiguration;
use Rector\FileFormatter\ValueObject\Indent;
use Rector\FileFormatter\ValueObjectFactory\EditorConfigConfigurationBuilder;

/**
 * @see \Rector\Tests\FileFormatter\Formatter\XmlFileFormatter\XmlFileFormatterTest
 */
final class XmlFileFormatter implements FileFormatterInterface
{
    /**
     * @var int
     */
    private $depth;

    /**
     * @var int
     */
    private $indent = 4;

    /**
     * @var string
     */
    private $padChar = ' ';

    /**
     * @var boolean
     */
    private $preserveWhitespace = false;

    public function __construct(
        private Formatter $xmlFormatter
    ) {
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        return $smartFileInfo->getExtension() === 'xml';
    }

    public function format(File $file, EditorConfigConfiguration $editorConfigConfiguration): void
    {
        $this->xmlFormatter->setIndentCharacter($editorConfigConfiguration->getIndentStyleCharacter());
        $this->xmlFormatter->setIndentSize($editorConfigConfiguration->getIndentSize());

        $newFileContent = $this->xmlFormatter->format($file->getFileContent());

        $newFileContent .= $editorConfigConfiguration->getFinalNewline();

        $file->changeFileContent($newFileContent);
    }

    public function createDefaultEditorConfigConfigurationBuilder(): EditorConfigConfigurationBuilder
    {
        $editorConfigConfigurationBuilder = new EditorConfigConfigurationBuilder();

        $editorConfigConfigurationBuilder->withIndent(Indent::createTabWithSize(1));

        return $editorConfigConfigurationBuilder;
    }

    /**
     * @param string $xml
     * @return string
     */
    private function formatXml($xml)
    {
        $output = '';
        $this->depth = 0;

        $parts = $this->getXmlParts($xml);

        if (strpos($parts[0], '<?xml') === 0) {
            $output = array_shift($parts) . PHP_EOL;
        }

        foreach ($parts as $part) {
            $output .= $this->getOutputForPart($part);
        }

        return trim($output);
    }

    /**
     * @param string $xml
     * @return array
     */
    private function getXmlParts($xml)
    {
        $withNewLines = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", trim($xml));
        return explode("\n", $withNewLines);
    }

    /**
     * @param string $part
     * @return string
     */
    private function getOutputForPart($part)
    {
        $output = '';
        $this->runPre($part);

        if ($this->preserveWhitespace) {
            $output .= $part . PHP_EOL;
        } else {
            $part = trim($part);
            $output .= $this->getPaddedString($part) . PHP_EOL;
        }

        $this->runPost($part);

        return $output;
    }

    /**
     * @param string $part
     */
    private function runPre($part)
    {
        if ($this->isClosingTag($part)) {
            $this->depth--;
        }
    }

    /**
     * @param string $part
     */
    private function runPost($part)
    {
        if ($this->isOpeningTag($part)) {
            $this->depth++;
        }
        if ($this->isClosingCdataTag($part)) {
            $this->preserveWhitespace = false;
        }
        if ($this->isOpeningCdataTag($part)) {
            $this->preserveWhitespace = true;
        }
    }

    /**
     * @param string $part
     * @return string
     */
    private function getPaddedString($part)
    {
        return str_pad($part, strlen($part) + ($this->depth * $this->indent), $this->padChar, STR_PAD_LEFT);
    }

    /**
     * @param string $part
     * @return boolean
     */
    private function isOpeningTag($part)
    {
        return (bool) preg_match('/^<[^\/]*>$/', $part);
    }

    /**
     * @param string $part
     * @return boolean
     */
    private function isClosingTag($part)
    {
        return (bool) preg_match('/^\s*<\//', $part);
    }

    /**
     * @param string $part
     * @return boolean
     */
    private function isOpeningCdataTag($part)
    {
        return strpos($part, '<![CDATA[') !== false;
    }

    /**
     * @param string $part
     * @return boolean
     */
    private function isClosingCdataTag($part)
    {
        return strpos($part, ']]>') !== false;
    }
}
