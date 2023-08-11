<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

class ConcatParamAppendReturnedUsedAsArg
{
    function formatSizeUnits($bytes): string
    {
        if ($bytes >= 1_073_741_824) {
            $bytes = number_format($bytes / 1_073_741_824, 1).' GB';
        } elseif ($bytes >= 1_048_576) {
            $bytes = number_format($bytes / 1_048_576, 1).' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 1).' KB';
        } elseif ($bytes > 1) {
            $bytes .= ' bytes';
        } elseif (1 === $bytes) {
            $bytes .= ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
