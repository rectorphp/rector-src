<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \Container $object */
$object['key'];
$object['key'] = 'value';
isset($object['key']);
unset($object['key']);

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \Container $object */
$object->get('key');
$object['key'] = 'value';
isset($object['key']);
unset($object['key']);

?>
