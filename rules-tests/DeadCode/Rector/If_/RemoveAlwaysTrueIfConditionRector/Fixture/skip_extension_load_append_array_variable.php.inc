<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipExtensionLoadAppendVariable
{
    public function verify()
    {
        $criticalErrors = [];

        if (!class_exists('XMLWriter')) {
            $criticalErrors[] = ["ERROR", "Your PHP has no XMLWriter support!", "Please install XMLWriter for PHP."];
        }
        if (!function_exists('mb_strlen')) {
            $criticalErrors[] = ["ERROR", "Your PHP has no MB String support!", "Please install the MB String extension for PHP."];
        }
        if (!extension_loaded('imagick') && !extension_loaded('gd')) {
            $criticalErrors[] = ["ERROR", "Your PHP has no imagick or GD support.", "Please install the imagick or GD extension for PHP."];
        }
        if (!function_exists('json_encode')) {
            $criticalErrors[] = ["ERROR", "Your PHP has no JSON support!", "Please install the JSON extension for PHP."];
        }
        if (!extension_loaded('gmp')) {
            $criticalErrors[] = ["ERROR", "Your PHP has no GMP support!", "Please install the GMP extension for PHP."];
        }
        if (!extension_loaded('filter')) {
            $criticalErrors[] = ["ERROR", "Your PHP has no filter support!", "Please install the filter extension for PHP."];
        }
        if (!@preg_match('/^\p{L}+$/u', "abc")) {
            $criticalErrors[] = ["ERROR", "Your PCRE library has no complete Unicode support. Please upgrade libpcre or compile with \"--enable-unicode-properties\"."];
        }
        if (!extension_loaded('zip')) {
            $criticalErrors[] = ["ERROR", "Your PHP has no ZIP support.", "Please install the ZIP extension for PHP."];
        }

        if (!empty($criticalErrors)) {
            echo "</head><body>\n";
            foreach ($criticalErrors as $criticalError) {
                call_user_func_array("StatusMessage", $criticalError);
                echo "<br><br>";
            }
        }
    }
}
