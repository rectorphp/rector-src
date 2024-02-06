<?php

declare(strict_types=1);

namespace Rector\Tests\CustomRules;

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\CustomRules\PhpCodeNodeClassesDetector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PhpCodeNodeClassesDetectorTest extends AbstractLazyTestCase
{
    private PhpCodeNodeClassesDetector $phpCodeNodeClassesDetector;

    protected function setUp(): void
    {
        $this->phpCodeNodeClassesDetector = $this->make(PhpCodeNodeClassesDetector::class);
    }

    /**
     * @param array<class-string<\PhpParser\Node>> $expectedNodeTypes
     */
    #[DataProvider('provideData')]
    public function test(string $phpContents, array $expectedNodeTypes): void
    {
        $foundClasses = $this->phpCodeNodeClassesDetector->detect($phpContents);
        $this->assertSame($expectedNodeTypes, $foundClasses);
    }

    public static function provideData(): \Iterator
    {
        yield ['echo "hi";', [Echo_::class, String_::class]];
    }
}
