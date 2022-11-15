<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

// @see https://github.com/laminas/laminas-session/blob/49964c47e6a4c00c35860014f5a65d0885645442/src/Validator/Id.php#L59
class SkipNumericString
{
    public function run()
    {
        $hashBitsPerChar = ini_get('session.sid_bits_per_character') ?: 5;

        switch ($hashBitsPerChar) {
            case 4:
                $pattern = '#^[0-9a-f]*$#';
                break;
            case 6:
                $pattern = '#^[0-9a-zA-Z-,]*$#';
                break;
            case 5:
                // intentionally fall-through
            default:
                $pattern = '#^[0-9a-v]*$#';
                break;
        }
    }
}
