<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

class Model {
    public function __isset($name) {
        return $name === 'foo';
    }
}

$model = new Model();
$fooDefined = isset($model->foo); // true
