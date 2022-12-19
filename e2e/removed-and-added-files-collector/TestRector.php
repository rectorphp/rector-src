<?php

declare(strict_types=1);

namespace Maintenance;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class TestRector extends AbstractRector
{
    private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;

    public function __construct(
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
    ) {
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Test adding file.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
                    function test() {}
                    CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
                    function test() {}
                    /* file named “test” is added */
                    CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Function_::class];
    }

    /**
     * @param Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Create a fixture.
        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent(
                __DIR__ . '/test',
                'test'
            )
        );

        return $new;
    }
}
