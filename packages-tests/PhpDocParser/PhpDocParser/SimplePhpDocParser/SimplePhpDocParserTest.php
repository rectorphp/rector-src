<?php

declare(strict_types=1);

namespace Rector\Tests\PhpDocParser\PhpDocParser\SimplePhpDocParser;

use Nette\Utils\FileSystem;
use Rector\PhpDocParser\PhpDocParser\SimplePhpDocParser;
use Rector\PhpDocParser\PhpDocParser\ValueObject\Ast\PhpDoc\SimplePhpDocNode;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class SimplePhpDocParserTest extends AbstractTestCase
{
    private SimplePhpDocParser $simplePhpDocParser;

    protected function setUp(): void
    {
        $this->boot();

        $this->simplePhpDocParser = $this->getService(SimplePhpDocParser::class);
    }

    public function testVar(): void
    {
        $fileContents = FileSystem::read(__DIR__ . '/Fixture/var_int.txt');

        $simplePhpDocNode = $this->simplePhpDocParser->parseDocBlock($fileContents);
        $this->assertInstanceOf(SimplePhpDocNode::class, $simplePhpDocNode);

        $varTagValues = $simplePhpDocNode->getVarTagValues();
        $this->assertCount(1, $varTagValues);
    }

    public function testParam(): void
    {
        $fileContents = FileSystem::read(__DIR__ . '/Fixture/param_string_name.txt');

        $simplePhpDocNode = $this->simplePhpDocParser->parseDocBlock($fileContents);
        $this->assertInstanceOf(SimplePhpDocNode::class, $simplePhpDocNode);

        // DX friendly
        $paramType = $simplePhpDocNode->getParamType('name');
        $withDollarParamType = $simplePhpDocNode->getParamType('$name');

        $this->assertSame($paramType, $withDollarParamType);
    }
}
