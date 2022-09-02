<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class InlinePublicTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureInlinePublic');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/inline_public_configured_rule.php';
    }
}
