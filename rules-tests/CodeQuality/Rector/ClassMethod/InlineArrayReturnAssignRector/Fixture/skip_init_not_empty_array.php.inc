<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipInitNotEmptyArray
{
     public function some(SomeObject $someObject): array
     {
        $message = [
           'type' => 'type',
            'id' => (string) $someObject->getId(),
        ];
        $message['link'] = $this->getSome($someObject);

       return $message;
     }
}