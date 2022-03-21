<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class DemoFile
{
    public function __construct(private array $data) {}
    
    public function run()
    {
    	unset($this->data['item']);
        
    }
}

?>
