<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

class EntityWithMagicGet
{
    protected array $_fields = [];

    public function __get(string $field)
    {
        $value = null;
        if (isset($this->_fields[$field])) {
            $value = &$this->_fields[$field];
        }

        return $value;
    }
}

$entity = new EntityWithMagicGet();
if ($entity->someProp) {
    preg_replace('/\r\n|\n|\r/', '', $entity->someProp);
}
