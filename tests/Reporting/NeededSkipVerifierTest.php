<?php

declare(strict_types=1);

namespace Rector\Tests\Reporting;

use Nette\Utils\FileSystem;
use Rector\Config\RectorConfig;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\Reporting\NeededSkipVerifier;
use Rector\Skipper\Skipper\Skipper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Reporting\Source\MakeClassFinalRector;
use Rector\ValueObject\Configuration;

final class NeededSkipVerifierTest extends AbstractLazyTestCase
{
    private string $tempDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempDirectory = sys_get_temp_dir() . '/rector_needed_skip_' . uniqid();
    }

    protected function tearDown(): void
    {
        FileSystem::delete($this->tempDirectory);

        SimpleParameterProvider::setParameter(Option::SKIP, []);
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, false);
    }

    public function testReportsSkipThatRuleWouldNotHaveChanged(): void
    {
        // the rule would turn this into "final class", so skipping it really prevents a change
        $neededFilePath = $this->tempDirectory . '/needed_skip.php';
        FileSystem::write($neededFilePath, '<?php class SomeClass {}', null);

        // already final, so the rule changes nothing - skipping it is unnecessary
        $unnecessaryFilePath = $this->tempDirectory . '/unnecessary_skip.php';
        FileSystem::write($unnecessaryFilePath, '<?php final class AlreadyFinal {}', null);

        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);
        SimpleParameterProvider::setParameter(Option::SKIP, [
            MakeClassFinalRector::class => [$neededFilePath, $unnecessaryFilePath],
        ]);

        $rectorConfig = $this->createContainerWithRule();

        // simulate the run skipping the rule on both files, so they are captured for verification
        $skipper = $rectorConfig->make(Skipper::class);
        $rule = $rectorConfig->make(MakeClassFinalRector::class);
        $this->assertTrue($skipper->shouldSkipRectorAndFile($rule, $neededFilePath));
        $this->assertTrue($skipper->shouldSkipRectorAndFile($rule, $unnecessaryFilePath));

        $neededSkipVerifier = $rectorConfig->make(NeededSkipVerifier::class);
        $unnecessarySkips = $neededSkipVerifier->verify(new Configuration(isDryRun: true));

        // one rule group, listing only the unnecessary file
        $this->assertCount(1, $unnecessarySkips);
        $this->assertStringContainsString(MakeClassFinalRector::class, $unnecessarySkips[0]);
        $this->assertStringContainsString('unnecessary_skip.php', $unnecessarySkips[0]);
        $this->assertStringNotContainsString('needed_skip.php', $unnecessarySkips[0]);
    }

    public function testReportsNothingWithoutCapturedSkips(): void
    {
        $rectorConfig = $this->createContainerWithRule();

        $neededSkipVerifier = $rectorConfig->make(NeededSkipVerifier::class);
        $this->assertSame([], $neededSkipVerifier->verify(new Configuration(isDryRun: true)));
    }

    private function createContainerWithRule(): RectorConfig
    {
        $rectorConfig = new LazyContainerFactory()->create();
        $rectorConfig->rule(MakeClassFinalRector::class);
        $rectorConfig->boot();

        return $rectorConfig;
    }
}
