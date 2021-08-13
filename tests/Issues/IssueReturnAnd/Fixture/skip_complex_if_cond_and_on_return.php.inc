<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueReturnAnd\Fixture;

class SkipComplexIfCondAndOnReturn
{
    public function run($a, $b, $c, $d, $e)
    {
        return $this->execute() &&
               isset($a) &&
               in_array($a, $b);
    }

    private function execute() {}
}

?>
