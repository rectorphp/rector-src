<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IssuePropertyPromoRemoveDelegatingParent\Source;

abstract class SomeParentWithEmptyConstruct
{
    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
		echo 'A init';
    }
}