<?php

declare(strict_types=1);

namespace Rector\Tests\Reporting;

use Nette\Utils\FileSystem;
use Rector\Config\RectorConfig;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\Reporting\NeededSkipVerifier;
use Rector\Skipper\Skipper\Skipper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Reporting\Source\MakeClassFinalRector;
use Rector\ValueObject\ProcessResult;

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

        $processResult = new ProcessResult([], [], 0, [], $skipper->provideSkippedFilePathsByRule());

        $neededSkipVerifier = $rectorConfig->make(NeededSkipVerifier::class);
        $unnecessarySkips = $neededSkipVerifier->verify($processResult);

        // one rule group, listing only the unnecessary file
        $this->assertCount(1, $unnecessarySkips);
        $this->assertStringContainsString(MakeClassFinalRector::class, $unnecessarySkips[0]);
        $this->assertStringContainsString('unnecessary_skip.php', $unnecessarySkips[0]);
        $this->assertStringNotContainsString('needed_skip.php', $unnecessarySkips[0]);

        // the verification is forced to a dry run, so the skipped file on disk stays untouched
        $this->assertSame('<?php class SomeClass {}', FileSystem::read($neededFilePath));
    }

    public function testReportsSkipCollectedByWorkers(): void
    {
        // already final, so the rule changes nothing - skipping it is unnecessary
        $unnecessaryFilePath = $this->tempDirectory . '/unnecessary_skip.php';
        FileSystem::write($unnecessaryFilePath, '<?php final class AlreadyFinal {}', null);

        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);
        SimpleParameterProvider::setParameter(Option::SKIP, [
            MakeClassFinalRector::class => [$unnecessaryFilePath],
        ]);

        $rectorConfig = $this->createContainerWithRule();

        // in a parallel run, the skipped files are captured by workers and only reach the main process via the result
        $processResult = new ProcessResult([], [], 0, [], [
            MakeClassFinalRector::class => [$unnecessaryFilePath],
        ]);

        $neededSkipVerifier = $rectorConfig->make(NeededSkipVerifier::class);
        $unnecessarySkips = $neededSkipVerifier->verify($processResult);

        $this->assertCount(1, $unnecessarySkips);
        $this->assertStringContainsString('unnecessary_skip.php', $unnecessarySkips[0]);
    }

    /**
     * @see https://github.com/rectorphp/rector/issues/9979
     */
    public function testKeepsBothSkipsWhenTwoRulesWouldMakeTheSameChange(): void
    {
        $filePath = $this->tempDirectory . '/shape.php';
        FileSystem::write($filePath, FileSystem::read(__DIR__ . '/Source/sealed_shape.php'), null);

        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);
        SimpleParameterProvider::setParameter(Option::SKIP, [
            RemoveAlwaysTrueIfConditionRector::class => [$filePath],
            RemoveDeadInstanceOfRector::class => [$filePath],
        ]);

        $rectorConfig = new LazyContainerFactory()->create();
        $rectorConfig->rules([RemoveDeadInstanceOfRector::class, RemoveAlwaysTrueIfConditionRector::class]);
        $rectorConfig->boot();

        // the real run registers processed files, so the sealed hierarchy is reflectable
        $dynamicSourceLocatorProvider = $rectorConfig->make(DynamicSourceLocatorProvider::class);
        $dynamicSourceLocatorProvider->addFiles([$filePath]);

        $processResult = new ProcessResult([], [], 0, [], [
            RemoveAlwaysTrueIfConditionRector::class => [$filePath],
            RemoveDeadInstanceOfRector::class => [$filePath],
        ]);

        $neededSkipVerifier = $rectorConfig->make(NeededSkipVerifier::class);

        // each rule alone changes the file, so neither skip is unnecessary
        $this->assertSame([], $neededSkipVerifier->verify($processResult));
    }

    public function testReportsNothingWithoutCapturedSkips(): void
    {
        $rectorConfig = $this->createContainerWithRule();

        $neededSkipVerifier = $rectorConfig->make(NeededSkipVerifier::class);
        $this->assertSame([], $neededSkipVerifier->verify(new ProcessResult([], [], 0)));
    }

    private function createContainerWithRule(): RectorConfig
    {
        $rectorConfig = new LazyContainerFactory()->create();
        $rectorConfig->rule(MakeClassFinalRector::class);
        $rectorConfig->boot();

        return $rectorConfig;
    }
}
