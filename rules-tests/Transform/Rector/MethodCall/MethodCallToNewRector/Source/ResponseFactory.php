<?php

namespace Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source;

class ResponseFactory
{
    public function createResponse(array $params): Response
    {
        return new Response($params);
    }
}
