<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\ReturnBinaryAndToEarlyReturnRector\Fixture;

class SkipComplexIfCondAndOnReturn
{
    public function run($a, $b)
    {
        return $this->execute() &&
               isset($a) &&
               in_array($a, $b);
    }

    private function execute() {}
}

?>
