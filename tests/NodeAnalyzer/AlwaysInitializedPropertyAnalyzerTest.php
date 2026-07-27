<?php

declare(strict_types=1);

namespace Rector\Tests\NodeAnalyzer;

use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\NodeAnalyzer\AlwaysInitializedPropertyAnalyzer;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;

final class AlwaysInitializedPropertyAnalyzerTest extends AbstractLazyTestCase
{
    private AlwaysInitializedPropertyAnalyzer $alwaysInitializedPropertyAnalyzer;

    private TestingParser $testingParser;

    private BetterNodeFinder $betterNodeFinder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->alwaysInitializedPropertyAnalyzer = $this->make(AlwaysInitializedPropertyAnalyzer::class);
        $this->testingParser = $this->make(TestingParser::class);
        $this->betterNodeFinder = $this->make(BetterNodeFinder::class);
    }

    #[DataProvider('provideData')]
    public function test(string $filePath, string $propertyName, bool $expectedIsAlwaysInitialized): void
    {
        $stmts = $this->testingParser->parseFileToDecoratedNodes($filePath);

        $classMethod = $this->betterNodeFinder->findFirstInstanceOf($stmts, ClassMethod::class);
        $this->assertInstanceOf(ClassMethod::class, $classMethod);

        $this->assertSame(
            $expectedIsAlwaysInitialized,
            $this->alwaysInitializedPropertyAnalyzer->isAlwaysInitialized($classMethod, $propertyName)
        );
    }

    /**
     * @return iterable<array{string, string, bool}>
     */
    public static function provideData(): iterable
    {
        $sourceDirectory = __DIR__ . '/Source/AlwaysInitializedProperty';

        yield [$sourceDirectory . '/DirectAssign.php', 'name', true];

        // never touched in the constructor
        yield [$sourceDirectory . '/DirectAssign.php', 'surname', false];

        // the early return skips the assign
        yield [$sourceDirectory . '/EarlyReturn.php', 'name', false];

        yield [$sourceDirectory . '/EveryBranchAssign.php', 'name', true];

        // the loop body might never run
        yield [$sourceDirectory . '/AssignInForeach.php', 'name', false];
    }

    public function testUnresolvedClassMethodIsNotInitialized(): void
    {
        $classMethod = new ClassMethod('__construct');

        $this->assertFalse($this->alwaysInitializedPropertyAnalyzer->isAlwaysInitialized($classMethod, 'name'));
    }
}
