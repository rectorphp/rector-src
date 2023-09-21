<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipReturnArrayDoc
{
    /**
     * @return array<string, string>
     */
    private function run()
    {
    }

    private function execute($data)
    {
    }

    public function runData()
    {
        $values = $this->run();
        $this->execute($values['data']);
    }
}
