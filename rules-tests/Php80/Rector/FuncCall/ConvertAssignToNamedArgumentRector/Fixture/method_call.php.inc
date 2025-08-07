<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

use Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Source\SomeClass;

final readonly class Service
{
    public function __construct(private SomeClass $someClass) {}

    public function methodCall()
    {
        $this->someClass->process($data = 'test', $options = ['key' => 'value']);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

use Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Source\SomeClass;

final readonly class Service
{
    public function __construct(private SomeClass $someClass) {}

    public function methodCall()
    {
        $this->someClass->process(data: 'test', options: ['key' => 'value']);
    }
}

?>
