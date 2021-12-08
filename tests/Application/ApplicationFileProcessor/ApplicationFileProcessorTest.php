<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Application\ApplicationFileProcessor;

use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObjectFactory\Application\FileFactory;
use Rector\Core\ValueObjectFactory\ProcessResultFactory;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class ApplicationFileProcessorTest extends AbstractTestCase
{
    private ApplicationFileProcessor $applicationFileProcessor;

    private FileFactory $fileFactory;

    private ProcessResultFactory $processResultFactory;

    protected function setUp(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/config/configured_rule.php']);

        $this->applicationFileProcessor = $this->getService(ApplicationFileProcessor::class);
        $this->fileFactory = $this->getService(FileFactory::class);
        $this->processResultFactory = $this->getService(ProcessResultFactory::class);
    }

    public function test(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture'], new Configuration());
        $this->assertCount(2, $files);

        $configuration = new Configuration(true);
        $systemErrorsAndFileDiffs = $this->applicationFileProcessor->run($files, $configuration);

        $processResult = $this->processResultFactory->create($systemErrorsAndFileDiffs);

        $this->assertCount(1, $processResult->getFileDiffs());
    }
}
