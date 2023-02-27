<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipNotNativelyTypedProperty
{
    /**
     * @var int
     */
    private $user;

    public function run()
    {
        $this->runData($this->user);
    }

    private function runData($user)
    {
    }
}
