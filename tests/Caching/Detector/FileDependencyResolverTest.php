<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Detector;

use Rector\Caching\Detector\FileDependencyResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;

final class FileDependencyResolverTest extends AbstractLazyTestCase
{
    private FileDependencyResolver $fileDependencyResolver;

    private TestingParser $testingParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileDependencyResolver = $this->make(FileDependencyResolver::class);
        $this->testingParser = $this->make(TestingParser::class);
    }

    public function testResolvesParentClassFile(): void
    {
        $childFilePath = __DIR__ . '/Source/ChildClassFixture.php';
        $file = $this->testingParser->parseFilePathToFile($childFilePath);

        $dependentFilePaths = $this->fileDependencyResolver->resolveDependentFilePaths($file);

        $this->assertContains(realpath(__DIR__ . '/Source/ParentClassFixture.php'), $dependentFilePaths);
        $this->assertNotContains(realpath($childFilePath), $dependentFilePaths);
    }

    public function testClassWithoutAncestorsHasNoDependencies(): void
    {
        $file = $this->testingParser->parseFilePathToFile(__DIR__ . '/Source/ParentClassFixture.php');

        $this->assertSame([], $this->fileDependencyResolver->resolveDependentFilePaths($file));
    }
}
