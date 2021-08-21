<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssuePartialImports\Fixture;

use Rector\Core\Tests\Issues\IssuePartialImports\Source;

final class AnotherClass
{
    public function run(Source\SomeClass $foo)
    {
    }
}

?>
