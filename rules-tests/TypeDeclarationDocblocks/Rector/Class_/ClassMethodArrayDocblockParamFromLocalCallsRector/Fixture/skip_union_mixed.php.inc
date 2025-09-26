<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class SkipUnionMixed
{
    public function run(string $content, string $contentNew)
    {
        $orig = json_decode($content, true);
        $new = json_decode($contentNew, true);

        $this->compareArrays($orig, $new);
    }

    private function compareArrays(array $array1, array $array2): void
    {
        foreach ($array1 as $key => $value1) {
            $value2 = $array2[$key];

            if (is_array($value1)) {
                $this->compareArrays($array1[$key], $array2[$key]);
                return;
            }

            if ($value1 !== $value2) {
                $this->diff[$key] = [
                    'existing' => $value1,
                    'new' => $value2,
                ];
            }
        }
    }
}