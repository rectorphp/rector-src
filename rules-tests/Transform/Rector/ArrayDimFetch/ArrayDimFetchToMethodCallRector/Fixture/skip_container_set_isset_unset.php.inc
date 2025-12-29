<?php

namespace Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\Fixture;

/** @var \Container $object */
$object['key'] = 'value';
isset($object['key']);
unset($object['key']);
