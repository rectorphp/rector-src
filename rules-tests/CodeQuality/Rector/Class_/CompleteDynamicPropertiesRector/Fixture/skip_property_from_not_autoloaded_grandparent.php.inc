<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

class SampleChild extends CancelableHandler
{
    public function getMessage()
    {
        return $this->configFactory->get('');
    }
}

class CancelableHandler extends \NotAutoloaded\EmailHandler
{
}
