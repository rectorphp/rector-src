<?php

declare(strict_types=1);

namespace Rector\Tests\Application;

use Rector\Application\RectorRegistry;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\ValueObject\Application\File;

final class RectorRegistryTest extends AbstractLazyTestCase
{
    private RectorRegistry $rectorRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        SimpleParameterProvider::setParameter(Option::SKIP, [
            FifthElement::class => ['*/skipped_directory/*'],
        ]);

        $this->rectorRegistry = $this->make(RectorRegistry::class);
        $this->rectorRegistry->refreshRectors([new FifthElement()]);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    public function testForFile(): void
    {
        $file = new File(__DIR__ . '/skipped_directory/some_file.php', '');
        $rectorsForPath = $this->rectorRegistry->forFile($file);

        $this->assertSame([], $rectorsForPath);

        $file = new File(__DIR__ . '/some_file.php', '');
        $rectorsForPath = $this->rectorRegistry->forFile($file);

        $this->assertCount(1, $rectorsForPath);
        $this->assertInstanceOf(FifthElement::class, $rectorsForPath[0]);
    }
}
