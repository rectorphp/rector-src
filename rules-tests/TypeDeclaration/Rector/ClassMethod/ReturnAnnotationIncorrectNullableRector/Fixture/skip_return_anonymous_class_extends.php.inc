<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnAnnotationIncorrectNullableRector\Fixture;

class SkipReturnAnonymousClassExtends
{
    public function getMockedRedis() : \Redis
    {
        $redis = new class() extends \Redis {
            public function set($key, $value, $timeout = null)
            {
                throw new \BadMethodCallException('Not implemented in mock object');
            }
        };

        return $redis;
    }
}


