<?php

declare(strict_types=1);

namespace Rector\Tests\Rector\RectorRunner;

use PhpParser\Node\Stmt\Class_;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\Rector\RectorRunner;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Rector\RectorRunner\Source\ReturnNullRector;
use Rector\ValueObject\Application\File;

/**
 * @see \Rector\Rector\RectorRunner
 */
final class RectorRunnerTest extends AbstractLazyTestCase
{
    private RectorRunner $rectorRunner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rectorRunner = $this->make(RectorRunner::class);

        $currentFileProvider = $this->make(CurrentFileProvider::class);
        $currentFileProvider->setFile(new File('some_file.php', '<?php'));
    }

    public function testReturnsNullWhenRuleMakesNoChange(): void
    {
        $return = $this->rectorRunner->run(new ReturnNullRector(), new Class_('SomeClass'));

        $this->assertNull($return);
    }
}
