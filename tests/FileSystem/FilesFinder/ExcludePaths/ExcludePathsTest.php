<?php

declare(strict_types=1);

namespace Rector\Tests\FileSystem\FilesFinder\ExcludePaths;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\FileSystem\FilesFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ExcludePathsTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, ['*/ShouldBeExcluded/*']);

        $filesFinder = $this->make(FilesFinder::class);

        $foundFileInfos = $filesFinder->findInDirectoriesAndFiles([__DIR__ . '/Source'], ['php']);
        $this->assertCount(1, $foundFileInfos);
    }
}
