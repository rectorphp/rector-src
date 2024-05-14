<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class SkipPossibleReturnFalse
{
    /**
     * @param array $data
     */
    public function canReturnNull($data)
    {
        $styles = [
            'key' => 'value',
        ];

        if (is_array($data)) {
            foreach ($data as $propertyName => $propertyData) {
                $data[$propertyName] = 'value';
            }
        }

        return $data;
    }
}
