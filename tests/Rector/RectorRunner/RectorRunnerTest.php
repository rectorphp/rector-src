<?php

declare(strict_types=1);

namespace Rector\Tests\Rector\RectorRunner;

use PhpParser\Node\Stmt\Class_;
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
    }

    public function testReturnsNullWhenRuleMakesNoChange(): void
    {
        $file = new File('some_file.php', '<?php');
        $return = $this->rectorRunner->run(new ReturnNullRector(), new Class_('SomeClass'), $file);

        $this->assertNull($return);
    }
}
