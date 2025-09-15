<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class UnionNest
{
    public function getData(): array
    {
        return [
            'field1' => 1111,
            'field2' => 22,
            'field3' => [
                'field1' => 'value1',
            ],
            'field4' => 9,
            'field5' => '50/50',
            'field6' => true,
            'field7' => 'value2',
            'field8' => [
                0 => [
                    'field1' => [
                        'field1' => 123.4,
                        'field2' => 200.0,
                        'field3' => 200.0,
                        'field4' => 10.0,
                        'field5' => 9.0,
                        'field6' => 210.0,
                        'field7' => 209.0,
                        'field8' => 3.0,
                        'field9' => 0.0,
                        'field10' => 123.4,
                    ],
                    'field2' => [
                        0 => [
                            'field1' => 3.0,
                        ],
                    ],
                    'field3' => [
                        0 => [
                            'field1' => 0.0,
                        ],
                    ],
                    'field4' => false,
                    'field5' => false,
                    'field6' => [
                        0 => [
                            'field1' => 'value3',
                        ],
                    ],
                    'field7' => [
                        'field1' => 'value4',
                    ],
                    'field8' => [],
                    'field9' => [
                        0 => [
                            'field1' => 123.4,
                            'field2' => 200.0,
                            'field3' => 200.0,
                            'field4' => 10.0,
                            'field5' => 9.0,
                            'field6' => 210.0,
                            'field7' => 209.0,
                            'field8' => 3.0,
                            'field9' => 0.0,
                            'field10' => 123.4,
                        ],
                    ],
                    'field10' => [],
                    'field11' => [
                        'field1' => '2000-01-01T00:00:00+0000',
                        'field2' => '2000-01-01T00:00:00+0000',
                        'field3' => '2000-01-01T00:00:00+0000',
                        'field4' => '2000-01-01T00:00:00+0000',
                        'field5' => '2000-01-01T00:00:00+0000',
                        'field6' => '2000-01-01T00:00:00+0000',
                        'field7' => '2000-01-01T00:00:00+0000',
                        'field8' => '2000-01-01T00:00:00+0000',
                    ],
                    'field12' => 'value5',
                    'field13' => [],
                ],
            ],
            'field9' => [
                'value6',
                'value7',
            ],
            'field10' => [
                'value8',
                'value9',
            ],
            'field11' => true,
            'field12' => 'value10',
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class UnionNest
{
    /**
     * @return array<string, int|string|bool|string[]|array{field1: array<string, float>, field2: array<0, array{field1: float}>, field3: array<0, array{field1: float}>, field4: bool, field5: bool, field6: array<0, array{field1: string}>, field7: array<'field1', string>, field8: array<never, never>, field9: array<0, array{field1: float, field2: float, field3: float, field4: float, field5: float, field6: float, field7: float, field8: float, field9: float, field10: float}>, field10: array<never, never>, field11: array<string, string>, field12: string, field13: array<never, never>}[]>
     */
    public function getData(): array
    {
        return [
            'field1' => 1111,
            'field2' => 22,
            'field3' => [
                'field1' => 'value1',
            ],
            'field4' => 9,
            'field5' => '50/50',
            'field6' => true,
            'field7' => 'value2',
            'field8' => [
                0 => [
                    'field1' => [
                        'field1' => 123.4,
                        'field2' => 200.0,
                        'field3' => 200.0,
                        'field4' => 10.0,
                        'field5' => 9.0,
                        'field6' => 210.0,
                        'field7' => 209.0,
                        'field8' => 3.0,
                        'field9' => 0.0,
                        'field10' => 123.4,
                    ],
                    'field2' => [
                        0 => [
                            'field1' => 3.0,
                        ],
                    ],
                    'field3' => [
                        0 => [
                            'field1' => 0.0,
                        ],
                    ],
                    'field4' => false,
                    'field5' => false,
                    'field6' => [
                        0 => [
                            'field1' => 'value3',
                        ],
                    ],
                    'field7' => [
                        'field1' => 'value4',
                    ],
                    'field8' => [],
                    'field9' => [
                        0 => [
                            'field1' => 123.4,
                            'field2' => 200.0,
                            'field3' => 200.0,
                            'field4' => 10.0,
                            'field5' => 9.0,
                            'field6' => 210.0,
                            'field7' => 209.0,
                            'field8' => 3.0,
                            'field9' => 0.0,
                            'field10' => 123.4,
                        ],
                    ],
                    'field10' => [],
                    'field11' => [
                        'field1' => '2000-01-01T00:00:00+0000',
                        'field2' => '2000-01-01T00:00:00+0000',
                        'field3' => '2000-01-01T00:00:00+0000',
                        'field4' => '2000-01-01T00:00:00+0000',
                        'field5' => '2000-01-01T00:00:00+0000',
                        'field6' => '2000-01-01T00:00:00+0000',
                        'field7' => '2000-01-01T00:00:00+0000',
                        'field8' => '2000-01-01T00:00:00+0000',
                    ],
                    'field12' => 'value5',
                    'field13' => [],
                ],
            ],
            'field9' => [
                'value6',
                'value7',
            ],
            'field10' => [
                'value8',
                'value9',
            ],
            'field11' => true,
            'field12' => 'value10',
        ];
    }
}

?>
