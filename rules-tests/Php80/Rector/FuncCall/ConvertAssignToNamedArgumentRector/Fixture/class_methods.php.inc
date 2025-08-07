<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

use Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Source\SomeClass;

/** @var SomeClass $service */
$service->process($data = 'test', $options = ['key' => 'value']);

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

use Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Source\SomeClass;

/** @var SomeClass $service */
$service->process(data: 'test', options: ['key' => 'value']);

?>
